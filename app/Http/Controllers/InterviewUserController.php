<?php

namespace App\Http\Controllers;

use App\Mail\InterviewUserResultMail;
use App\Models\Applicants;
use App\Models\Decision;
use App\Models\InterviewUser;
use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InterviewUserController extends Controller
{
    /**
     * Menampilkan daftar pelamar yang lolos interview HR untuk penilaian interview user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $applicants = Applicants::with(['opportunity', 'InterviewHr'])
            ->whereHas('InterviewHr', function ($query) {
                $query->whereIn('decision_id', [2, 3]);
            })
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->get();

        // Buat data InterviewUser otomatis jika belum ada
        foreach ($applicants as $applicant) {
            InterviewUser::firstOrCreate(
                ['applicant_id' => $applicant->id],
                [
                    'score' => 0,
                    'decision_id' => 1, // Default decision_id
                    'notes' => '-',
                    'event_date' => now(), // Menambahkan nilai untuk event_date
                    'location' => '-',
                    'notification_sent' => false,
                    'staff_id' => Auth::id(), // Inisialisasi status notifikasi
                ]
            );
        }

        // Ambil data applicant dengan paginasi setelah data InterviewUser dibuat
        $applicants = Applicants::with(['opportunity', 'InterviewHr', 'InterviewUser.staff'])
            ->whereHas('InterviewHr', function ($query) {
                $query->whereIn('decision_id', [2, 3]);
            })
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('admin.interview_user.index', compact('applicants', 'search'));
    }

    /**
     * Menampilkan detail Psikotest.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $InterviewUser = InterviewUser::where('applicant_id', $id)->first();
        $applicant = Applicants::findOrFail($id);

        return view('admin.interview_user.show', compact('InterviewUser', 'applicant'));
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
        $InterviewUser = InterviewUser::where('applicant_id', $id)->first();

        $decisions = Decision::all();

        return view('admin.interview_user.edit', compact('applicant', 'InterviewUser', 'decisions'));
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
            'notes' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string'
        ]);

        // Ambil data InterviewUser yang sudah ada
        $InterviewUser = InterviewUser::where('applicant_id', $id)->firstOrFail();

        // Update nilai pada InterviewUser
        $InterviewUser->score = $request->score;
        $InterviewUser->decision_id = $request->decision_id;
        $InterviewUser->notes = $request->notes;
        $InterviewUser->event_date = $request->event_date;
        $InterviewUser->location = $request->location;
        $InterviewUser->staff_id = Auth::id();

        // --- Logika Validasi Nilai Default ---
        // 1. Cek jika decision_id masih default (ID 1)
        if ($request->decision_id == 1) {
            return redirect()->back()->with('error', 'Decision has not been selected. Please select a decision other than the default.');
        }

        // 2. Jika decision_id BUKAN 1 (yaitu 2, 3, atau 4),
        //    maka skor atau catatan tidak boleh berisi nilai default.
        $requestedScore = $request->score;
        $requestedNotes = trim($request->notes); // Hapus spasi di awal/akhir catatan
        $requestedLocation = trim($request->location);

        if ($requestedScore === 0 || $requestedNotes === '-' || $requestedLocation === '-') {
            return redirect()->back()->with('error', 'Score or Notes still contains default values. Please fill in the complete data.');
        }


        // Logika untuk notifikasi_terkirim:
        // Jika decision_id berubah DAN keputusan baru adalah 'Disarankan' (2) atau 'Tidak Disarankan' (4),
        // maka reset notifikasi_terkirim menjadi false agar bisa dikirim ulang/pertama kali.
        if ($InterviewUser->isDirty('decision_id') && in_array($InterviewUser->decision_id, [2, 3, 4])) {
            $InterviewUser->notification_sent = false;
        }
        // Jika decision_id tidak berubah, atau berubah ke nilai lain,
        // maka notifikasi_terkirim tidak diubah di sini.
        // Hanya method sendNotification yang akan mengubahnya menjadi true.

        $InterviewUser->save();

        if ($InterviewUser->decision_id == 4) { // 4 adalah contoh untuk "Tidak Disarankan"
            // Hapus data terkait di tabel lain
            Offering::where('applicant_id', $InterviewUser->applicant_id)->delete();
        }
        return redirect()->route('admin.interview_user.index')->with('success', 'User Interview Updated Successfully');
    }
    /**
     * Mengirim notifikasi hasil Psikotest.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification($id)
    {
        $applicant = Applicants::with('InterviewUser.decision')->findOrFail($id);
        $InterviewUser = $applicant->InterviewUser;

        // Pastikan ada data psikotest dan keputusannya
        if (!$InterviewUser || !$InterviewUser->decision) {
            return redirect()->back()->with('error', 'User Interview data is incomplete');
        }

        // Cek jika notifikasi sudah pernah dikirim untuk keputusan saat ini
        if ($InterviewUser->notification_sent) {
            return redirect()->back()->with('error', 'Notifications have already been sent for this applicant.');
        }

        // Hanya ID 2 (Disarankan/Lolos) dan 4 (Tidak Disarankan/Gagal) yang boleh kirim notifikasi
        if (!in_array($InterviewUser->decision_id, [2, 3, 4])) {
            return redirect()->back()->with('error', 'Notifications have already been sent for this applicant and are final. Resubmissions are not permitted.');
        }


        // Validasi skor dan catatan untuk keputusan "Disarankan", "Netral", dan "Tidak Disarankan"
        $score = trim($InterviewUser->score);
        $notes = trim($InterviewUser->notes);
        $location = trim($InterviewUser->location);

        // Jika keputusan adalah 2, 3, atau 4, skor dan catatan tidak boleh default (0 atau -)
        if (($InterviewUser->decision_id == 2 || $InterviewUser->decision_id == 3 || $InterviewUser->decision_id == 4) && ($score === 0 || $notes === '-' || $location === '-')) {
            return redirect()->back()->with('error', 'Scores and notes are required for this decision.');
        }
        // --- Tentukan string hasil untuk email ---
        $emailResultString = '';
        if ($InterviewUser->decision_id == 2 || $InterviewUser->decision_id == 3) {
            $emailResultString = 'lolos'; // Disarankan dan Netral dianggap lolos
        } elseif ($InterviewUser->decision_id == 4) {
            $emailResultString = 'gagal'; // Tidak Disarankan dianggap gagal
        } else {
            // Ini seharusnya tidak tercapai karena sudah ada validasi di atas,
            // tapi sebagai fallback keamanan.
            $emailResultString = 'unknown';
        }

        try {
            // Kirim notifikasi email ke pelamar
            // Asumsi CvScreeningResultMail menerima objek $applicant dan $screening
            Mail::to($applicant->email)->send(new InterviewUserResultMail($applicant,  $emailResultString));

            // Tandai notifikasi sudah terkirim
            $InterviewUser->notification_sent = true;
            $InterviewUser->save();

            return redirect()->back()->with('success', 'Notifikasi berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error('Failed to send custom info email to applicant_id ' . $applicant->id . ' - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send notification. Please try again.');
        }
    }
}
