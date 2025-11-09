<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default_amount',
    ];

    public function salaryLogs()
    {
        return $this->belongsToMany(SalaryLog::class, 'salary_log_component')->withPivot('amount');
    }
}
