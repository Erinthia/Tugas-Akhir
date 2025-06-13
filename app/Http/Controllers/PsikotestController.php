<?php

namespace App\Http\Controllers;

use App\Mail\PsikotestCustomInfoMail;
use App\Mail\PsikotestResultMail; // Pastikan ini ada dan sesuai dengan lokasi Mailable Anda
use App\Models\Applicants;
use App\Models\Decision;
use App\Models\InterviewHR;
use App\Models\InterviewUser;
use App\Models\Offering;
use App\Models\Psikotest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PsikotestController extends Controller
{
    /**
     * Menampilkan daftar pelamar yang lolos CV Screening untuk penilaian Psikotest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil data pelamar yang lolos CV Screening (decision_id 2 atau 3)
        // dan eager load relasi yang dibutuhkan
        $applicants = Applicants::with(['opportunity', 'ScreeningCv', 'psikotest.staff']) // Eager load psikotest.decision
            ->whereHas('ScreeningCv', function ($query) {
                $query->whereIn('decision_id', [2, 3]);
            })
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        // Buat data psikotest otomatis jika belum ada untuk setiap pelamar yang lolos CV Screening
        foreach ($applicants as $applicant) {
            Psikotest::firstOrCreate(
                ['applicant_id' => $applicant->id],
                [
                    'score' => 0,
                    'decision_id' => 1, // Asumsi ID 1 adalah 'Belum Dinilai' atau default
                    'notes' => '-',
                    'notification_sent' => false, // Inisialisasi status notifikasi
                    'staff_id' => Auth::id(),
                ]
            );
        }


        return view('admin.psikotests.index', compact('applicants', 'search'));
    }

    /**
     * Menampilkan detail Psikotest.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $applicant = Applicants::with('psikotest.decision')->findOrFail($id); // Eager load decision untuk psikotest
        $psikotest = $applicant->psikotest; // Ambil psikotest dari relasi applicant

        return view('admin.psikotests.show', compact('psikotest', 'applicant'));
    }

    /**
     * Menampilkan form untuk mengedit data Psikotest.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $applicant = Applicants::findOrFail($id);
        // Gunakan firstOrCreate untuk memastikan Psikotest selalu ada
        $psikotest = Psikotest::firstOrCreate(
            ['applicant_id' => $applicant->id],
            [
                'score' => 0,
                'decision_id' => 1, // Asumsi ID 1 adalah 'Belum Dinilai' atau default
                'notes' => '-',
                'notification_sent' => false,
                'info_sent' => false
            ]
        );

        $decisions = Decision::all();

        return view('admin.psikotests.edit', compact('applicant', 'psikotest', 'decisions'));
    }

    /**
     * Memperbarui data Psikotest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id ID Pelamar (ini adalah applicant_id)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|numeric',
            'decision_id' => 'required|exists:decisions,id',
            'notes' => 'nullable|string',
        ]);

        // Ambil data psikotest yang sudah ada
        $psikotest = Psikotest::where('applicant_id', $id)->firstOrFail();

        // Simpan nilai decision_id yang lama sebelum diupdate (opsional, untuk debugging)
        // $olddecisionId = $psikotest->decision_id;

        // Update nilai pada psikotest
        $psikotest->score = $request->score;
        $psikotest->decision_id = $request->decision_id;
        $psikotest->notes = $request->notes;
        $psikotest->staff_id = Auth::id();

        // --- Logika Validasi Nilai Default ---
        // 1. Cek jika decision_id masih default (ID 1)
        if ($request->decision_id == 1) {
            return redirect()->back()->with('error', 'Keputusan belum dipilih. Silakan pilih keputusan selain default.');
        }

        // 2. Jika decision_id BUKAN 1 (yaitu 2, 3, atau 4),
        //    maka skor atau catatan tidak boleh berisi nilai default.
        $requestedScore = $request->score;
        $requestedNotes = trim($request->notes); // Hapus spasi di awal/akhir catatan

        if ($requestedScore === 0 || $requestedNotes === '-') {
            return redirect()->back()->with('error', 'Skor atau Catatan masih berisi nilai default. Harap isi data lengkap.');
        }


        // Logika untuk notifikasi_terkirim:
        // Jika decision_id berubah DAN keputusan baru adalah 'Disarankan' (2) atau 'Tidak Disarankan' (4),
        // maka reset notifikasi_terkirim menjadi false agar bisa dikirim ulang/pertama kali.
        if ($psikotest->isDirty('decision_id') && in_array($psikotest->decision_id, [2, 3, 4])) {
            $psikotest->notification_sent = false;
        }
        // Jika decision_id tidak berubah, atau berubah ke nilai lain,
        // maka notifikasi_terkirim tidak diubah di sini.
        // Hanya method sendNotification yang akan mengubahnya menjadi true.

        $psikotest->save();

        // Logika penghapusan data terkait jika keputusan adalah 'Tidak Disarankan' (4)
        if ($psikotest->decision_id == 4) {
            InterviewHR::where('applicant_id', $psikotest->applicant_id)->delete();
            InterviewUser::where('applicant_id', $psikotest->applicant_id)->delete();
            Offering::where('applicant_id', $psikotest->applicant_id)->delete();
        }

        return redirect()->route('admin.psikotests.index')->with('success', 'Psikotest berhasil diperbarui.');
    }

    /**
     * Mengirim notifikasi hasil Psikotest.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification($id)
    {
        $applicant = Applicants::with('Psikotest.decision')->findOrFail($id);
        $psikotest = $applicant->Psikotest;

        // Pastikan ada data psikotest dan keputusannya
        if (!$psikotest || !$psikotest->decision) {
            return redirect()->back()->with('error', 'Data psikotest belum lengkap.');
        }

        // Cek jika notifikasi sudah pernah dikirim untuk keputusan saat ini
        if ($psikotest->notification_sent) {
            return redirect()->back()->with('error', 'Notifikasi sudah pernah dikirim untuk keputusan ini.');
        }

        // Hanya ID 2 (Disarankan/Lolos) dan 4 (Tidak Disarankan/Gagal) yang boleh kirim notifikasi
        if (!in_array($psikotest->decision_id, [2, 3, 4])) {
            return redirect()->back()->with('error', 'Keputusan ini tidak dapat dikirimi notifikasi.');
        }

        // Validasi skor dan catatan untuk keputusan "Disarankan", "Netral", dan "Tidak Disarankan"
        $score = trim($psikotest->score);
        $notes = trim($psikotest->notes);

        // Jika keputusan adalah 2, 3, atau 4, skor dan catatan tidak boleh default (0 atau -)
        if (($psikotest->decision_id == 2 || $psikotest->decision_id == 3 || $psikotest->decision_id == 4) && ($score === 0 || $notes === '-')) {
            return redirect()->back()->with('error', 'Skor dan catatan wajib diisi untuk keputusan ini.');
        }
        // --- Tentukan string hasil untuk email ---
        $emailResultString = '';
        if ($psikotest->decision_id == 2 || $psikotest->decision_id == 3) {
            $emailResultString = 'lolos'; // Disarankan dan Netral dianggap lolos
        } elseif ($psikotest->decision_id == 4) {
            $emailResultString = 'gagal'; // Tidak Disarankan dianggap gagal
        } else {
            // Ini seharusnya tidak tercapai karena sudah ada validasi di atas,
            // tapi sebagai fallback keamanan.
            $emailResultString = 'unknown';
        }

        try {
            // Kirim notifikasi email ke pelamar
            // Asumsi CvScreeningResultMail menerima objek $applicant dan $screening
            Mail::to($applicant->email)->send(new PsikotestResultMail($applicant,  $emailResultString));

            // Tandai notifikasi sudah terkirim
            $psikotest->notification_sent = true;
            $psikotest->save();

            return redirect()->back()->with('success', 'Notifikasi berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi email untuk applicant_id: ' . $applicant->id . ' - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim notifikasi. Silakan coba lagi.');
        }
    }
    public function showCustomEmailForm($id)
    {
        $applicant = Applicants::findOrFail($id);
        $psikotest = $applicant->psikotest;

        if ($psikotest->info_sent) {
            return redirect()->route('admin.psikotests.index')
                ->with('error', 'Additional information has already been sent for this applicant.');
        }

        return view('admin.psikotests.custom_email_form', compact('applicant'));
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
        $psikotest = $applicant->psikotest;

        try {
            Mail::to($applicant->email)->send(new PsikotestCustomInfoMail($applicant, $request->message));

            // Tandai info sudah dikirim
            $psikotest->info_sent = true;
            $psikotest->save();

            return redirect()->route('admin.psikotests.index')->with('success', 'Notification sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send custom info email to applicant_id ' . $id . ' : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send notification. Please try again.');
        }
    }
}
