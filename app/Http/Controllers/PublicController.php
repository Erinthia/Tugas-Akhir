<?php

namespace App\Http\Controllers;


use App\Models\Applicants;
use App\Models\Education;
use App\Models\Gender;
use App\Models\GraduatedStatus;
use App\Models\Marital;
use App\Models\Opportunity;
use App\Models\Religion;
use App\Models\ScreeningCv;
use App\Models\SourceInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantSubmittedMail;



class PublicController extends Controller
{

    public function index()
    {
        $opportunities = Opportunity::latest()->get();
        return view('welcome', compact('opportunities'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:applicant,email',
            'phone_number' => 'required|string|max:15',
            'portfolio_link' => 'nullable|url',
            'cv_file' => 'required|file|mimes:pdf|max:2048',
            'gender_id' => 'required|integer', // Foreign key untuk gender
            'birth_date' => 'required|date',
            'domicile_address' => 'required|string',
            'religion_id' => 'required|integer', // Foreign key untuk agama
            'marital_id' => 'required|integer', // Foreign key untuk status pernikahan
            'education_id' => 'required|integer', // Foreign key untuk tingkat pendidikan
            'education_institution' => 'required|string',
            'majority' => 'required|string',
            'gpa' => 'nullable|numeric|max:5',
            'graduate_status' => 'required|string',
            'graduate_year' => 'required|integer',
            'information_from' => 'nullable|string',
        ]);

        // Simpan file CV dan dapatkan path
        // Simpan file CV dan dapatkan path
        $cvFile = $request->file('cv_file');

        // Periksa ekstensi file dan pastikan itu adalah PDF
        if ($cvFile->getClientOriginalExtension() !== 'pdf') {
            return redirect()->back()->with('error', 'Only PDF files are allowed for CV.');
        }

        // Simpan file dengan nama unik dan ekstensi yang benar
        $cvPath = $cvFile->storeAs(
            'cvs',
            'cv-' . time() . '.' . $cvFile->getClientOriginalExtension(),
            'public'
        );

        // Buat instance baru untuk pelamar
        $applicant = new Applicants();

        $applicant->id = (string) Str::uuid();
        $applicant->id_opportunity = $id;
        $applicant->fullname = $request->fullname;
        $applicant->email = $request->email;
        $applicant->phone_number = $request->phone_number;
        $applicant->portfolio_link = $request->portfolio_link;
        $applicant->cv_file = $cvPath;
        $applicant->gender_id = $request->gender_id;
        $applicant->birth_date = $request->birth_date;
        $applicant->domicile_address = $request->domicile_address;
        $applicant->religion_id = $request->religion_id;
        $applicant->marital_id = $request->marital_id;
        $applicant->education_id = $request->education_id;
        $applicant->education_institution = $request->education_institution;
        $applicant->majority = $request->majority;
        $applicant->gpa = $request->gpa;
        $applicant->graduate_status = $request->graduate_status;
        $applicant->graduate_year = $request->graduate_year;
        $applicant->information_from = $request->information_from;

        // Menyimpan data dan mengembalikan respon
        try {

            $ScreeningCv = new ScreeningCv;

            $ScreeningCv->applicant_id = $applicant->id;
            $ScreeningCv->decision_id = 1;
            $ScreeningCv->score = 0;
            $ScreeningCv->notes = '-';

            $applicant->save();
            $ScreeningCv->save();
            // dd($ScreeningCv);

            //  Kirim notifikasi email ke pelamar
            Mail::to($applicant->email)->send(new ApplicantSubmittedMail([
                'name' => $applicant->fullname,
                'position' => optional($applicant->opportunity)->name ?? 'Posisi yang dilamar',
            ]));

            return redirect()->back()->with('success', 'Application submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data berhasil disimpan, tapi gagal mengirim email.');
        }
    }
    public function show($id)
    {
        $opportunity = Opportunity::find($id);

        $genders = Gender::all();
        $religions = Religion::all();  // Ambil semua data religion
        $maritalStatuses = Marital::all();  // Ambil semua data marital status
        $educations = Education::all();  // Ambil semua data education
        $graduate_status = GraduatedStatus::all();
        $informationSource = SourceInformation::all();

        if ($opportunity) {
            $opportunity->clicked = $opportunity->clicked + 1;
            $opportunity->save();

            // Kirim data ke view
            return view('detailopportunity', compact('opportunity', 'genders', 'religions', 'maritalStatuses', 'educations', 'graduate_status', 'informationSource'));
        } else {
            return redirect()->route('opportunity.index')->with('error', 'Opportunity not found.');
        }
    }
}
