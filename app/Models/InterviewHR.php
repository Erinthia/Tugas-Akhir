<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewHR extends Model
{
    use HasFactory;

    protected $table = 'interview_hr';
    protected $fillable = [
        'name',
        'applicant_id',
        'event_date',
        'location',
        'score',
        'notes',
        'decision_id',
        'notification_sent',
        'info_sent'

    ];

    public function decision()
    {
        return $this->belongsTo(Decision::class);
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
