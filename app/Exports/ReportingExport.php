<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambahkan ini

class ReportingExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $applicants;

    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    public function collection()
    {
        $data = $this->applicants->map(function ($applicant) { // Iterasi melalui Applicants
            return [
                'Applicant Name' => $applicant->fullname ?? '-',
                'Opportunity' => $applicant->opportunity->name ?? '-',
                'CV Screening' => $applicant->ScreeningCv->decision->name ?? '-',
                'Psikotest' => $applicant->Psikotest->decision->name ?? '-',
                'User Interview' => $applicant->InterviewUser->decision->name ?? '-',
                'HR Interview' => $applicant->InterviewHr->decision->name ?? '-',
                'Offering' => $applicant->Offering->decision->name ?? '-',
            ];
        });
        return $data;
    }

    public function headings(): array
    {
        return [
            'Applicant Name',
            'Opportunity',
            'CV Screening',
            'Psikotest',
            'User Interview',
            'HR Interview',
            'Offering',
        ];
    }
}
