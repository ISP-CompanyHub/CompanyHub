<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_from',
        'period_until',
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_until' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'salary_log_component')->withPivot('amount');
    }

    public function getGrossSalaryAttribute()
    {
        return $this->salaryComponents()->where('amount', '>', 0)->sum('amount');
    }

    public function getNetSalaryAttribute()
    {
        return $this->salaryComponents()->sum('amount');
    }
}
