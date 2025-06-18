<?php

namespace App\Http\Controllers;

use App\Mail\OfferingResultMail;
use App\Models\Applicants;
use App\Models\Decision;
use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Pastikan ini di-import jika digunakan
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging
class OfferingController extends Controller
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

        $applicants = Applicants::with(['opportunity', 'InterviewUser'])
            ->whereHas('InterviewUser', function ($query) {
                $query->whereIn('decision_id', [2, 3]);
            })
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->get();

        // Buat data Offering otomatis jika belum ada
        foreach ($applicants as $applicant) {
            Offering::firstOrCreate(
                ['applicant_id' => $applicant->id],
                [
                    'benefit' => '-',
                    'selection_result' => '-',
                    'deadline_offering' => now()->toDateString(),
                    'offering_result' => '-',
                    'staff_id' => Auth::id(),
                ]
            );
        }

        // Ambil data applicant dengan paginasi setelah data Offering dibuat
        $applicants = Applicants::with(['opportunity', 'InterviewUser', 'Offering.staff'])
            ->whereHas('InterviewUser', function ($query) {
                $query->whereIn('decision_id', [2, 3]);
            })
            ->when($search, function ($query, $search) {
                return $query->where('fullname', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('admin.offerings.index', compact('applicants', 'search'));
    }
    /**
     * Menampilkan detail CV Screening.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\View\View
     */

    public function show($id)
    {
        $Offering = Offering::where('applicant_id', $id)->first();
        $applicant = Applicants::findOrFail($id);

        return view('admin.offerings.show', compact('Offering', 'applicant'));
    }
    /**
     * Menampilkan form untuk mengedit data CV Screening.
     *
     * @param  string  $id ID Pelamar (ini adalah applicant_id)
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $applicant = Applicants::findOrFail($id);
        $Offering = Offering::where('applicant_id', $id)->first();

        $decisions = Decision::all();

        return view('admin.offerings.edit', compact('applicant', 'Offering', 'decisions'));
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
            'benefit' => 'nullable|string',
            'selection_result' => 'nullable|string',
            'deadline_offering' => 'nullable|date',
            'offering_result' => 'nullable|string'

        ]);

        // Ambil data Offering yang sudah ada
        $Offering = Offering::where('applicant_id', $id)->firstOrFail();

        // Update nilai pada Offering
        $Offering->benefit = $request->benefit;
        $Offering->selection_result = $request->selection_result;
        $Offering->deadline_offering = $request->deadline_offering;
        $Offering->offering_result = $request->offering_result;
        $Offering->staff_id = Auth::id();

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

        $Offering->save();
        return redirect()->route('admin.offerings.index')->with('success', 'Offering Updated Successully');
    }
    /**
     * Mengirim notifikasi hasil CV Screening.
     *
     * @param  string  $id ID Pelamar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification($id)
    {
        $applicant = Applicants::with('Offering')->findOrFail($id);
        $Offering = $applicant->Offering;

        if (!$Offering || !$Offering->benefit || !$Offering->selection_result || !$Offering->deadline_offering || !$Offering->offering_result) {
            return redirect()->back()->with('error', 'Offering data is incomplete.');
        }

        // Anggap semua data offering valid, maka dianggap "lolos"
        $result = 'lolos';

        Mail::to($applicant->email)->send(new OfferingResultMail($applicant, $result));

        return redirect()->back()->with('success', 'Notification sent successfully.');
    }
}
