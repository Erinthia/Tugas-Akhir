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
            'benefit' => 'required|string',
            'selection_result' => 'required|string',
            'deadline_offering' => 'required|date',
            'offering_result' => 'required|string'

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
        // Cek nilai default
        if (
            trim($request->benefit) === '-' || trim($request->benefit) === '' ||
            trim($request->selection_result) === '-' || trim($request->selection_result) === '' ||
            trim($request->offering_result) === '-' || trim($request->offering_result) === ''
        ) {
            return redirect()->back()->with('error', 'Please change the default values of benefit, selection result, offering result before saving.');
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
        $applicant = Applicants::with('offering')->findOrFail($id);
        $Offering = $applicant->offering;

        // Cek kalau sudah dikirim, tolak pengiriman ulang
        if ($Offering->notification_sent) {
            return redirect()->back()->with('error', 'Notification has already been sent.');
        }

        Mail::to($applicant->email)->send(new OfferingResultMail($applicant, $Offering));

        // Tandai sudah dikirim
        $Offering->notification_sent = true;
        $Offering->save();

        return redirect()->back()->with('success', 'Notification sent successfully.');
    }
}
