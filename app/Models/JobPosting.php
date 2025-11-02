<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'is_active',
        'posted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'is_active' => 'boolean',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }

    /**
     * Get the candidates for the job posting.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get the job offers for the job posting.
     */
    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
