<?php

namespace App\Http\Controllers;

use App\Mail\CvScreeningCustomInfoMail;
use App\Models\Applicants;
use App\Models\Decision;
use App\Models\InterviewHR;
use App\Models\InterviewUser;
use App\Models\Offering;
use App\Models\Psikotest;
use App\Models\ScreeningCv;
use Illuminate\Http\Request;
use App\Mail\CvScreeningResultMail; // Pastikan ini ada dan sesuai dengan lokasi Mailable Anda
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Pastikan ini di-import jika digunakan
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging

class ScreeningCvController extends Controller
{
    /**
     * Menampilkan daftar CV Screening dengan fitur pencarian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data pelamar dengan eager loading relasi yang dibutuhkan
        // ScreeningCv::all() di atas tidak digunakan, bisa dihapus.
        $applicants = Applicants::with(['opportunity', 'ScreeningCv.decision', 'ScreeningCv.staff'])
            ->when($search, function ($query) use ($search) {
                // Memfilter berdasarkan nama lengkap pelamar
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Menerapkan paginasi

        return view('admin.cv_screenings.index', compact('applicants', 'search'));
    }

    /**
     * Menampilkan detail CV Screening.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $selectedApplicant = Applicants::findOrFail($id);
        // Menggunakan firstOrCreate untuk memastikan ScreeningCv selalu ada
        $ScreeningCv = ScreeningCv::firstOrCreate(
            ['applicant_id' => $selectedApplicant->id],
            [
                'score' => 0,
                'decision_id' => 1, // Asumsi ID 1 adalah default/belum diseleksi
                'notes' => '-',
                'notification_sent' => false,
            ]
        );
        return view('admin.cv_screenings.show', compact('ScreeningCv', 'selectedApplicant'));
    }

    /**
     * Memperbarui data CV Screening.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id ID CV Screening (ini adalah ID dari record ScreeningCv, bukan applicant_id)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|numeric',
            'decision_id' => 'required|exists:decisions,id',
            'notes' => 'nullable|string',
        ]);

        $ScreeningCv = ScreeningCv::findOrFail($id);
        // Set nilai baru dari request
        $ScreeningCv->score = $request->score;
        $ScreeningCv->decision_id = $request->decision_id;
        $ScreeningCv->notes = $request->notes;
        $ScreeningCv->staff_id = Auth::id();

        // --- Logika Validasi Nilai Default ---
        // 1. Cek jika decision_id masih default (ID 1)
        if ($request->decision_id == 1) {
            return redirect()->back()->with('error', 'Decision has not been selected. Please select a decision other than the default.');
        }

        // 2. Jika decision_id BUKAN 1 (yaitu 2, 3, atau 4),
        //    maka skor atau catatan tidak boleh berisi nilai default.
        $requestedScore = $request->score;
        $requestedNotes = trim($request->notes); // Hapus spasi di awal/akhir catatan

        if ($requestedScore === 0 || $requestedNotes === '-') {
            return redirect()->back()->with('error', 'Score or Notes still contains default values. Please fill in the complete data.');
        }

        // Logika untuk notifikasi_terkirim:
        // Jika decision_id berubah DAN keputusan baru adalah 'Disarankan' (2), 'Netral' (3), atau 'Tidak Disarankan' (4),
        // maka reset notifikasi_terkirim menjadi false agar bisa dikirim ulang/pertama kali.
        // Ini memastikan bahwa setiap kali keputusan berubah ke status yang bisa dikirim notifikasi,
        // status notifikasi di-reset agar HRD bisa mengirim ulang.
        if ($ScreeningCv->isDirty('decision_id') && in_array($ScreeningCv->decision_id, [2, 3, 4])) {
            $ScreeningCv->notification_sent = false;
        }
        // Jika decision_id tidak berubah, atau berubah ke nilai lain,
        // maka notifikasi_terkirim tidak diubah di sini.
        // Hanya method sendNotification yang akan mengubahnya menjadi true.

        $ScreeningCv->save();

        // Logika penghapusan data terkait jika keputusan adalah 'Tidak Disarankan' (4)
        if ($ScreeningCv->decision_id == 4) {
            // Pastikan Anda menggunakan applicant_id dari ScreeningCv
            Psikotest::where('applicant_id', $ScreeningCv->applicant_id)->delete();
            InterviewHR::where('applicant_id', $ScreeningCv->applicant_id)->delete();
            InterviewUser::where('applicant_id', $ScreeningCv->applicant_id)->delete();
            Offering::where('applicant_id', $ScreeningCv->applicant_id)->delete();
        }

        return redirect()->route('admin.cv_screenings.index')->with('success', 'Cv Screening Updated Successfully.');
    }

    /**
     * Menampilkan form untuk mengedit data CV Screening.
     *
     * @param  string  $id ID Pelamar (ini adalah applicant_id)
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $selectedApplicant = Applicants::with('ScreeningCv')->findOrFail($id);

        // Gunakan firstOrCreate untuk memastikan ScreeningCv selalu ada
        // Jika belum ada, akan dibuatkan record baru dengan nilai default
        $ScreeningCv = ScreeningCv::firstOrCreate(
            ['applicant_id' => $selectedApplicant->id], // Kondisi untuk mencari
            [ // Nilai default jika record baru dibuat
                'score' => 0,
                'decision_id' => 1, // Asumsi ID 1 adalah 'Belum Diseleksi' atau default
                'notes' => '-',
                'notification_sent' => false,
            ]
        );

        $decisions = Decision::all(); // Pastikan model decision diimpor
        return view('admin.cv_screenings.edit', compact('selectedApplicant', 'decisions', 'ScreeningCv'));
    }
    /**
     * Mengirim notifikasi hasil CV Screening.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification($id)
    {
        $applicant = Applicants::with('ScreeningCv.decision')->findOrFail($id);
        $ScreeningCv = $applicant->ScreeningCv;

        // Pastikan ada data screening dan keputusan
        if (! $ScreeningCv || ! $ScreeningCv->decision) {
            return redirect()->back()->with('error', 'CV Screening data is incomplete.');
        }

        // Hanya ID 2 (Disarankan) dan 4 (Tidak Disarankan) yang boleh kirim notifikasi
        if (!in_array($ScreeningCv->decision_id, [2, 3, 4])) {
            return redirect()->back()->with('error', 'Notifications have already been sent for this applicant and are final. Resubmissions are not permitted..');
        }
        // Cek jika notifikasi sudah pernah dikirim untuk keputusan saat ini
        if ($ScreeningCv->notification_sent) {
            return redirect()->back()->with('error', 'Notifications have already been sent for this applicant.');
        }


        // --- Tentukan string hasil untuk email ---
        $emailResultString = '';
        if ($ScreeningCv->decision_id == 2 ||  $ScreeningCv->decision_id == 3) {
            $emailResultString = 'lolos'; // Disarankan dan Netral dianggap lolos
        } elseif ($ScreeningCv->decision_id == 4) {
            $emailResultString = 'gagal'; // Tidak Disarankan dianggap gagal
        } else {
            // Ini seharusnya tidak tercapai karena sudah ada validasi di atas,
            // tapi sebagai fallback keamanan.
            $emailResultString = 'unknown';
        }

        try {
            // Kirim notifikasi email ke pelamar
            // Asumsi CvScreeningResultMail menerima objek $applicant dan $screening
            Mail::to($applicant->email)->send(new CvScreeningResultMail($applicant,  $emailResultString));

            // Tandai notifikasi sudah terkirim
            $ScreeningCv->notification_sent = true;
            $ScreeningCv->save();

            return redirect()->back()->with('success', 'Notification sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send email notification for applicant_id: ' . $applicant->id . ' - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send notification. Please try again.');
        }
    }
    public function showCustomEmailForm($id)
    {
        $applicant = Applicants::findOrFail($id);
        $ScreeningCv = $applicant->ScreeningCv;

        if ($ScreeningCv->info_sent) {
            return redirect()->route('admin.cv_screenings.index')
                ->with('error', 'Additional information has already been sent for this applicant.');
        }

        return view('admin.cv_screenings.custom_email_form', compact('applicant'));
    }

    /**
     * Mengirim notifikasi hasil CV Screening.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendCustomEmail(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $applicant = Applicants::findOrFail($id);
        $ScreeningCv = $applicant->ScreeningCv;

        try {
            Mail::to($applicant->email)->send(new CvScreeningCustomInfoMail($applicant, $request->message));

            // Tandai info sudah dikirim
            $ScreeningCv->info_sent = true;
            $ScreeningCv->save();

            return redirect()->route('admin.cv_screenings.index')->with('success', 'Notification sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send custom info email to applicant_id ' . $id . ' : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send notification. Please try again.');
        }
    }
}
