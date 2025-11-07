<?php

namespace App\Services;

use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyStructurePdfService
{
    public function generate()
    {
        $departments = Department::with(['lead', 'employees'])->get();

        $pdf = Pdf::loadView('pdf.company-structure', compact('departments'));

        return $pdf->download('company_structure.pdf');
    }
}
