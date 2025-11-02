<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'job_posting_id',
        'name',
        'email',
        'phone',
        'resume_url',
        'status',
        'notes',
    ];

    /**
     * Get the job posting that the candidate applied to.
     */
    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    /**
     * Get the interviews for the candidate.
     */
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    /**
     * Get the job offer for the candidate.
     */
    public function jobOffer(): HasOne
    {
        return $this->hasOne(JobOffer::class);
    }
}
