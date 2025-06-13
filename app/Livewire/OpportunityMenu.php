<?php

namespace App\Livewire;

use App\Models\Applicants;
use App\Models\Schema;
use Livewire\Component;
use App\Models\Category;
use App\Models\Division;
use App\Models\Opportunity;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class OpportunityMenu extends Component
{

    public $applicants;
    public $selectedApplicant = null;
    
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $isHome = true;
    public $search = '';
    public $isDetail = false;
    public $isCreate = false;
    public $isInformation = false;
    public $isUpdate = false;
    public $isDestroy = false;
    // input form
    public $name;
    public $description;
    public $job_description;
    public $job_requirement;
    public $division;
    public $category;
    public $schema;
    public $quotas;
    public $location;
    public $open_date;
    public $close_date;
    
    // Update form
    public $update_name;
    public $update_description;
    public $update_job_description;
    public $update_job_requirement;
    
    public $opportunity;
    public $schemas;
    public $categories;
    public $divisions;
    public $id;
    

    public function render()
    {
        
        // Mengambil data applicants dan mengurutkannya berdasarkan id secara ascending
        $opportunities = Opportunity::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(6);
        $applicants = Applicants::orderBy('id', 'ASC')->get(); // Urutkan berdasarkan id secara ascending
    
        return view('livewire.opportunity-menu', [
            'opportunities' => $opportunities,
            'applicants' => $applicants,
        ]);
    }
    
    


    public function home(){
        $this->isHome = true;
        $this->isDetail = false;
        $this->isCreate = false;
        $this->isInformation = false;
        $this->isUpdate = false;

        $this->reset('name', 'description', 'job_description', 'job_requirement', 'quotas', 'location', 'schema', 'open_date', 'close_date', 'opportunity');
    }

    public function detail($id)
    {
        // Ambil kesempatan berdasarkan ID
        $this->opportunity = Opportunity::find($id);
    
        // Ambil applicants yang sesuai dengan id_opportunity
        $this->applicants = Applicants::where('id_opportunity', $id)->get();
    
        // Atur status tampilan
        $this->isHome = false;
        $this->isDetail = true;
        $this->isCreate = false;
        $this->isInformation = false;
        $this->isUpdate = false;
    
    }
    

    public function create()
    {
        $this->isHome = false;
        $this->isCreate = true;
        $this->isDetail = false;
        $this->isInformation = false;
        $this->isUpdate = false;

        $this->schemas = Schema::all();
        $this->categories = Category::all();
        $this->divisions = Division::all();
    }

    public function store()
    {
        
        $this->validate([
            'name' => 'required',
            'description' => 'required',
            'job_description' => 'required',
            'job_requirement' => 'required',
            'quotas' => 'required',
            'location' => 'required',
            'division' => 'required',
            'category' => 'required',
            'schema' => 'required',
            'open_date' => 'required',
            'close_date' => 'required|after:open_date',
        ]);

        Opportunity::create([
            'name' => $this->name,
            'description' => $this->description,
            'job_description' => $this->job_description,
            'job_requirements' => $this->job_requirement,
            'quota' => $this->quotas,
            'location' => $this->location,
            'division_id' => $this->division,
            'category_id' => $this->category,
            'schema_id' => $this->schema,
            'start_date' => $this->open_date,
            'end_date' => $this->close_date,
        ]);

        session()->flash('success', 'Opportunity created successfully.');
        $this->home();
    }

    public function information($id)
    {
        $this->opportunity = Opportunity::find($id);
        $this->isHome = false;
        $this->isDetail = false;
        $this->isCreate = false;
        $this->isInformation = true;
        $this->isUpdate = false;
    }

    public function update($id)
{
    $opportunity = Opportunity::find($id);

    $this->id = $opportunity->id;
    $this->name = $opportunity->name;
    $this->description = $opportunity->description;
    $this->job_description = $opportunity->job_description;
    $this->job_requirement = $opportunity->job_requirements;
    $this->division = $opportunity->division_id;
    $this->category = $opportunity->category_id;
    $this->quotas = $opportunity->quota;
    $this->location = $opportunity->location;
    $this->open_date = $opportunity->start_date;
    $this->close_date = $opportunity->end_date;

    $this->schemas = Schema::all();
    $this->categories = Category::all();
    $this->divisions = Division::all();

    $this->isHome = false;
    $this->isDetail = false;
    $this->isCreate = false;
    $this->isInformation = false;
    $this->isUpdate = true;
}

    public function destroy($id) 
    {
        $opportunity = Opportunity::find($id);
    
        if ($opportunity) {
            $opportunity->delete(); // Soft delete
            session()->flash('success', 'Opportunity deleted successfully.');
        } else {
            session()->flash('error', 'Opportunity not found.');
        }
    
        $this->home();
    }
    public function mount()
    {
        // Ambil daftar applicants dari database
        $this->applicants = Applicants::all();
    }

    // Fungsi untuk memilih applicant
    public function selectApplicant($id)
    {
        $this->selectedApplicant = Applicants::find($id);
    }
    public function setUpdate()
{
    $this->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'job_description' => 'required|string|max:255',
        'job_requirement' => 'required|string|max:255',
        'division' => 'required|integer',
        'category' => 'required|integer',
        'quotas' => 'required|integer',
        'location' => 'required|string|max:255',
        'open_date' => 'required|date',
        'close_date' => 'required|date|after:open_date',
    ]);

    $opportunity = Opportunity::find($this->id);

    $opportunity->update([
        'name' => $this->name,
        'description' => $this->description,
        'job_description' => $this->job_description,
        'job_requirements' => $this->job_requirement,
        'division_id' => $this->division,
        'category_id' => $this->category,
        'quota' => $this->quotas,
        'location' => $this->location,
        'start_date' => $this->open_date,
        'end_date' => $this->close_date,
    ]);

    session()->flash('success', 'Opportunity updated successfully.');
    $this->home();
}

    }