<?php

namespace App\Models;

use App\Models\Schema;
use App\Models\Category;
use App\Models\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'job_description',
        'job_requirements',
        'clicked',
        'quota',
        'location',
        'division_id',
        'category_id',
        'schema_id',
        'start_date',
        'end_date',
    ];

    // Relasi
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    // Query Scope untuk kesempatan aktif
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now());
    }

    // Query Scope untuk kesempatan tidak aktif (deactive)
    public function scopeDeactive($query)
    {
        return $query->where('end_date', '<', now());
    }
    public function applicants()
    {
        return $this->hasMany(Applicants::class, 'id_opportunity');
    }
    public function Reporting()
    {
        return $this->hasOne(Reporting::class, 'id_opportunity');
    }

    public function mount()
    {
        // Inisialisasi applicants sebagai koleksi kosong
        $this->applicants = collect();
    }

    // Fungsi untuk memilih job dan mengambil data applicant berdasarkan id_opportunity
    public function render()
    {
        $opportunities = Opportunity::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        return view('livewire.opportunity-menu', [
            'opportunities' => $opportunities,
            'applicants' => $this->applicants,
        ]);
    }

    public function selectJob($jobId)
    {
        $this->selectedJob = Opportunity::find($jobId);
        $this->applicants = $this->selectedJob
            ? Applicants::where('id_opportunity', $jobId)->get()
            : collect();

        $this->resetPage();
    }
}
