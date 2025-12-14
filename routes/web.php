<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CompanyStructureController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\VacationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = [];

    if (auth()->check() && auth()->user()->can('view job postings')) {
        $stats['activeJobPostings'] = \App\Models\JobPosting::where('is_active', true)->count();
        $stats['totalJobPostings'] = \App\Models\JobPosting::count();
    }

    if (auth()->check() && auth()->user()->can('view candidates')) {
        $stats['newCandidates'] = \App\Models\Candidate::where('status', 'new')->count();
        $stats['totalCandidates'] = \App\Models\Candidate::count();
    }

    if (auth()->check() && auth()->user()->can('view interviews')) {
        $stats['upcomingInterviews'] = \App\Models\Interview::where('scheduled_at', '>', now())->count();
        $stats['totalInterviews'] = \App\Models\Interview::count();
    }

    if (auth()->check() && auth()->user()->can('view job offers')) {
        $stats['sentOffers'] = \App\Models\JobOffer::where('status', 'sent')->count();
        $stats['totalOffers'] = \App\Models\JobOffer::count();
    }

    return view('dashboard', compact('stats'));
})
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

// Recruitment Management Routes
Route::middleware(['auth', 'verified', 'role:administrator|manager'])->group(function () {
    // Job Postings
    Route::resource('job-postings', JobPostingController::class);

    // Candidates
    Route::resource('candidates', CandidateController::class);
    Route::patch('candidates/{candidate}/status', [CandidateController::class, 'updateStatus'])
        ->name('candidates.update-status');

    // Interviews (handled by CandidateController)
    Route::get('interviews', [CandidateController::class, 'interviewIndex'])->name('interviews.index');
    Route::get('interviews/create', [CandidateController::class, 'interviewCreate'])->name('interviews.create');
    Route::post('interviews', [CandidateController::class, 'interviewStore'])->name('interviews.store');
    Route::get('interviews/{interview}', [CandidateController::class, 'interviewShow'])->name('interviews.show');
    Route::get('interviews/{interview}/edit', [CandidateController::class, 'interviewEdit'])->name('interviews.edit');
    Route::put('interviews/{interview}', [CandidateController::class, 'interviewUpdate'])->name('interviews.update');
    Route::delete('interviews/{interview}', [CandidateController::class, 'interviewDestroy'])->name('interviews.destroy');
});

// Job Offers (Manager only) - handled by JobPostingController
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('job-offers', [JobPostingController::class, 'offerIndex'])->name('job-offers.index');
    Route::get('job-offers/create', [JobPostingController::class, 'offerCreate'])->name('job-offers.create');
    Route::post('job-offers', [JobPostingController::class, 'offerStore'])->name('job-offers.store');
    Route::get('job-offers/{jobOffer}', [JobPostingController::class, 'offerShow'])->name('job-offers.show');
    Route::get('job-offers/{jobOffer}/edit', [JobPostingController::class, 'offerEdit'])->name('job-offers.edit');
    Route::put('job-offers/{jobOffer}', [JobPostingController::class, 'offerUpdate'])->name('job-offers.update');
    Route::delete('job-offers/{jobOffer}', [JobPostingController::class, 'offerDestroy'])->name('job-offers.destroy');
    Route::get('job-offers/{jobOffer}/preview', [JobPostingController::class, 'offerPreview'])
        ->name('job-offers.preview');
    Route::get('job-offers/{jobOffer}/download', [JobPostingController::class, 'offerDownload'])
        ->name('job-offers.download');
});

// Salary Management (Manager only)
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('salaries/monthly', [SalaryController::class, 'monthly'])->name('salaries.monthly');
    Route::post('salaries/monthly/send', [SalaryController::class, 'sendEmail'])->name('salaries.monthly.send');
    Route::resource('salaries', SalaryController::class);
});

// Documents
Route::middleware(['auth'])->group(function () {
    Route::resource('documents', DocumentController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
});

// Vacations
Route::middleware(['auth'])->group(function () {
    Route::resource('holidays', HolidayController::class);

    Route::get('vacation/approvals', [VacationController::class, 'approvals'])->name('vacation.approvals');
    Route::post('vacation/{vacation}/approve', [VacationController::class, 'approve'])->name('vacation.approve');
    Route::resource('holidays', HolidayController::class);
    Route::post('vacation/leave-balance', [VacationController::class, 'leaveBalance'])->name('vacation.leave_balance');
    Route::get('vacation/leave-balance', [VacationController::class, 'leaveBalanceForm'])
        ->name('vacation.leave_balance');

    Route::post('vacation/leave-balance', [VacationController::class, 'leaveBalanceGenerate'])
        ->name('vacation.leave_balance.generate');
    Route::resource('vacation', VacationController::class);
    Route::post('/vacation/{vacation}/reject', [VacationController::class, 'reject'])->name('vacation.reject');

});

Route::middleware(['auth'])->group(function () {
    Route::get('profiles', [EmployeeController::class, 'index'])
        ->name('profiles.index');

    Route::get('profiles/create', [EmployeeController::class, 'create'])->name('profiles.create');

    Route::post('profiles/create', [EmployeeController::class, 'store'])
        ->name('profiles.store');

    Route::get('profiles/{employee}', [EmployeeController::class, 'show'])
        ->name('profiles.show');

    Route::get('profiles/{employee}/edit', [EmployeeController::class, 'edit'])
        ->name('profiles.edit');

    Route::put('profiles/{employee}', [EmployeeController::class, 'update'])
        ->name('profiles.update');
});

// Departments Management (Administrator | Manager)
Route::middleware(['auth', 'verified', 'role:administrator|manager'])->prefix('departments')->name('departments.')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::get('{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('{department}', [DepartmentController::class, 'update'])->name('update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('company-structure', [CompanyStructureController::class, 'index'])->name('company-structure.index');
    Route::post('company-structure/pdf', [CompanyStructureController::class, 'generatePdf'])->name('company-structure.pdf');
});

require __DIR__ . '/auth.php';
