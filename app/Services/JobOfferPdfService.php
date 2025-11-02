<?php

namespace App\Services;

use App\Models\JobOffer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class JobOfferPdfService
{
    /**
     * Generate PDF for job offer.
     */
    public function generate(JobOffer $jobOffer): string
    {
        $jobOffer->load(['candidate', 'jobPosting']);

        $pdf = Pdf::loadView('job-offers.pdf.template', [
            'jobOffer' => $jobOffer,
            'candidate' => $jobOffer->candidate,
            'jobPosting' => $jobOffer->jobPosting,
        ]);

        $filename = "job-offer-{$jobOffer->offer_number}.pdf";
        $path = "job-offers/{$filename}";

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * Download PDF for job offer.
     */
    public function download(JobOffer $jobOffer): \Illuminate\Http\Response
    {
        $jobOffer->load(['candidate', 'jobPosting']);

        $pdf = Pdf::loadView('job-offers.pdf.template', [
            'jobOffer' => $jobOffer,
            'candidate' => $jobOffer->candidate,
            'jobPosting' => $jobOffer->jobPosting,
        ]);

        return $pdf->download("job-offer-{$jobOffer->offer_number}.pdf");
    }

    /**
     * Preview PDF inline.
     */
    public function preview(JobOffer $jobOffer): \Illuminate\Http\Response
    {
        $jobOffer->load(['candidate', 'jobPosting']);

        $pdf = Pdf::loadView('job-offers.pdf.template', [
            'jobOffer' => $jobOffer,
            'candidate' => $jobOffer->candidate,
            'jobPosting' => $jobOffer->jobPosting,
        ]);

        return $pdf->stream("job-offer-{$jobOffer->offer_number}.pdf");
    }
}
