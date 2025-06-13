<?php

namespace App\Mail;

use App\Models\Applicants;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferingResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $Offering;

    public function __construct(Applicants $applicant, $Offering)
    {
        $this->applicant = $applicant;
        $this->Offering = $Offering;
    }

    public function build()
    {
        return $this->subject('Hasil Offering')
            ->view('emails.offering_result')
            ->with([
                'applicant' => $this->applicant,
                'Offering' => $this->Offering, // penting untuk blade
            ]);
    }
}
