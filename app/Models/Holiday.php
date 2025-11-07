<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    // Migration created a table named "holiday" (singular).
    protected $table = 'holiday';

    protected $fillable = [
        'holiday_date',
        'title',
        'type',
    ];

    protected $casts = [
        'holiday_date' => 'datetime',
    ];

    // If you want timestamps (created_at/updated_at), leave as-is.
    // The migration did not add timestamps; if you don't want them on the model:
    public $timestamps = false;
}
