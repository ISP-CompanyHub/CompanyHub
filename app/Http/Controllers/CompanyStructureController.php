<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\CompanyStructurePdfService;

class CompanyStructureController extends Controller
{
    public function index(): View
    {
        $departments = Department::with(['lead', 'employees'])->get();

        return view('company-structure', compact('departments'));
    }

    public function generatePdf(CompanyStructurePdfService $pdfService)
    {
        return $pdfService->generate(); // returns PDF response
    }
}
