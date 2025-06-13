<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offering extends Model
{
    use HasFactory;

    protected $table = 'offerings';
    protected $fillable = [
        'name',
        'applicant_id',
        'benefit',
        'selection_result',
        'deadline_offering',
        'offering_result',

    ];


    public function applicant()
    {
        return $this->belongsTo(Applicants::class, 'applicant_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
