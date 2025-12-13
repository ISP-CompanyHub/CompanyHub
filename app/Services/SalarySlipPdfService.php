<?php

namespace App\Services;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SalarySlipPdfService
{
    /**
     * Generate PDF for salary slip.
     *
     * @param User $user
     * @param string $month YYYY-MM
     * @param array $reportData Calculated data
     * @return string Path to stored PDF
     */
    public function generate(User $user, string $month, array $reportData): string
    {
        $pdf = Pdf::loadView('salaries.pdf.template', [
            'user' => $user,
            'month' => $month,
            'reportData' => $reportData,
        ]);

        $monthName = \Carbon\Carbon::parse($month)->format('Y-m');
        $filename = "salary-slip-{$user->id}-{$monthName}.pdf";
        $path = "salary-slips/{$filename}";

        Storage::put($path, $pdf->output());

        return $path;
    }
}
