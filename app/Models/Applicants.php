<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicants extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'applicant';
    protected $keyType = 'string';

    protected $fillable = [
        'id',                    // Sesuaikan dengan kolom primary key
        'id_opportunity',        // Foreign key untuk opportunity
        'fullname',              // Nama lengkap
        'email',                 // Email pelamar
        'phone_number',          // Nomor telepon
        'gender_id',             // Foreign key untuk gender
        'birth_date',            // Tanggal lahir
        'domicile_address',      // Alamat domisili
        'religion_id',           // Foreign key untuk agama
        'marital_id',            // Foreign key untuk status pernikahan
        'education_id',          // Foreign key untuk tingkat pendidikan
        'education_institution', // Nama institusi pendidikan
        'majority',              // Jurusan
        'gpa',                   // IPK
        'graduate_status',       // Status kelulusan
        'graduate_year',         // Tahun lulus
        'information_from',      // Sumber informasi pekerjaan
        'portfolio_link',        // Link portofolio
        'cv_file',               // File CV
    ];

    // Definisikan relasi dengan model lain
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'id_opportunity');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(Marital::class, 'marital_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
    public function graduateStatus()
    {
        return $this->belongsTo(GraduatedStatus::class, 'graduate_status');
    }
    public function screeningCv()
    {
        return $this->hasOne(ScreeningCv::class, 'applicant_id');
    }
    public function psikotest()
    {
        return $this->hasOne(Psikotest::class, 'applicant_id');
    }
    public function interviewHR()
    {
        return $this->hasOne(InterviewHR::class, 'applicant_id');
    }
    public function interviewUser()
    {
        return $this->hasOne(InterviewUser::class, 'applicant_id');
    }
    public function offering()
    {
        return $this->hasOne(Offering::class, 'applicant_id');
    }
    public function reporting()
    {
        return $this->hasOne(Reporting::class, 'applicant_id');
    }
}
