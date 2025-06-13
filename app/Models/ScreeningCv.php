<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningCv extends Model
{
    use HasFactory;
    protected $table = 'screening_cv';
    protected $fillable = [
        'name',
        'applicant_id',
        'decision_id', // Ini adalah decision_id dari CV Screening
        'score',
        'notes',
        'notification_sent',

    ];
    public function decision()
    {
        return $this->belongsTo(Decision::class, 'decision_id'); // Relasi ke keputusan CV Screening
    }
    public function applicant()
    {
        return $this->belongsTo(Applicants::class, 'applicant_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
