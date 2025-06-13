<?php

namespace App\Livewire;

use App\Models\Applicants;
use App\Models\Decision;
use App\Models\Opportunity;
use Livewire\Component;
use Livewire\WithPagination;

class CvScreeningMenu extends Component
{
    use WithPagination;

    public $isHome = true;
    public $isCreate = false;
    public $isEdit = false;
    public $isShow = false;

    public $selectedApplicant = null;
    public $fullname;
    public $email;
    public $phone_number;
    public $cv_file;

    public $score;
    public $decision_id;
    public $notes;

    public $search = '';

    public $decisions;
    public $opportunities;
    public $id;
    public $isUpdate;
    public $id_applicant;



    public function mount()
    {
        $this->decisions = Decision::all();
        $this->opportunities = Opportunity::all();
    }

    public function save()
    {
        // Validate input fields
        $this->validate([
            'score' => 'required|numeric|max:100',
            'decision_id' => 'required|exists:decisions,id',
            'notes' => 'required|string|max:255',
        ]);

        // Check if the selected applicant already has a CV screening record
        $existingCvScreening = CvScreeningMenu::where('id_applicant', $this->selectedApplicant->id)->first();

        if ($existingCvScreening) {
            session()->flash('error', 'Applicant already has a CV screening.');
            return;
        }

        // Create new CV screening if not existing
        CvScreeningMenu::create([
            'id_applicant' => $this->selectedApplicant->id,
            'decision_id' => $this->decision_id,
            'score' => $this->score,
            'notes' => $this->notes,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        session()->flash('success', 'Data berhasil disimpan.');
        $this->resetInputFields();
        $this->back();
    }



    public function render()
    {
        $applicants = Applicants::with('cvScreening')
            ->where('fullname', 'like', '%' . $this->search . '%')
            ->paginate(5);
        $opportunities = Opportunity::all();

        return view('livewire.cv-screening-menu', [
            'applicants' => $applicants,

        ]);
    }

    public function back()
    {
        $this->isHome = true;
        $this->isCreate = false;
        $this->isEdit = false;
        $this->isShow = false;
        $this->resetInputFields();
    }

    public function selectApplicant($id)
    {
        $applicant = Applicants::find($id);

        if ($applicant) {
            $this->selectedApplicant = $applicant;
        } else {
            session()->flash('error', 'Applicant tidak ditemukan.');
        }
    }

    protected function resetInputFields()
    {
        $this->fullname = null;
        $this->email = null;
        $this->phone_number = null;
        $this->cv_file = null;

        $this->score = null;
        $this->decision_id = null;
        $this->notes = null;

        $this->selectedApplicant = null;
    }

    public function rules()
    {
        return [
            'score' => 'required|integer|min:1|max:100',
            'decision_id' => 'required|exists:decisions,id',
            'notes' => 'nullable|string|max:255',
        ];
    }
    public function edit($id)
    {
        $cvScreening = CvScreeningMenu::find($id);

        if ($cvScreening) {
            // Assign details to Livewire properties
            $this->id = $cvScreening->id;
            $this->decision_id = $cvScreening->decision_id;
            $this->score = $cvScreening->score;
            $this->notes = $cvScreening->notes;

            $this->isEdit = true;
            $this->isHome = false;
            $this->isCreate = false;
            $this->isShow = false;

            // Jika applicant terkait tidak ditemukan, beri feedback ke pengguna
            if (!$cvScreening->applicant) {
                session()->flash('error', 'Applicant terkait tidak ditemukan.');
                $this->back(); // Kembali ke halaman utama
            }
        } else {
            session()->flash('error', 'Data tidak ditemukan untuk applicant ini.');
            $this->back(); // Kembali ke halaman utama
        }
    }


    public function show($id)
    {
        // Mengatur tampilan halaman
        $this->isHome = false;
        $this->isCreate = false;
        $this->isUpdate = false;
        $this->isShow = true;

        // Mendapatkan data applicant berdasarkan ID
        $applicant = Applicants::find($id);

        // Mengecek apakah applicant ditemukan
        if ($applicant) {
            $this->fullname = $applicant->fullname;
            $this->email = $applicant->email;
            $this->phone_number = $applicant->phone_number;
            $this->cv_file = $applicant->cv_file;
            $this->selectedApplicant = $applicant; // Mengatur applicant yang dipilih
        } else {
            session()->flash('error', 'Applicant not found.');
            $this->back(); // Kembali ke halaman utama jika applicant tidak ditemukan
        }
    }




    public function create($id)
    {
        $applicant = Applicants::find($id);

        if ($applicant) {
            // Assign the applicant details for a new entry
            $this->selectedApplicant = $applicant;
            $this->fullname = $applicant->fullname;
            $this->email = $applicant->email;
            $this->phone_number = $applicant->phone_number;
            $this->cv_file = $applicant->cv_file;

            $this->isHome = false;
            $this->isEdit = true; // Set mode to edit for a new entry
        } else {
            session()->flash('error', 'Applicant tidak ditemukan.');
            $this->back();
        }
    }
}
