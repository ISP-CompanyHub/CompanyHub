<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    /**
     * The table associated with the model.
     * The migration created a table called "vacation_request" (singular),
     * so we explicitly set the table name here to ensure Eloquent uses it.
     */
    protected $table = 'vacation_request';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'submission_date',
        'vacation_start',
        'vacation_end',
        'type',
        'status',
        'comments',
    ];

    /**
     * Cast attributes to appropriate types.
     */
    protected $casts = [
        'submission_date' => 'datetime',
        'vacation_start'  => 'datetime',
        'vacation_end'    => 'datetime',
    ];

    /**
     * The migration did not include created_at/updated_at timestamps,
     * so disable automatic timestamps on the model.
     */
    public $timestamps = false;

    /**
     * Optionally, you can provide some helper constants or scopes.
     * Uncomment or adapt these to your app's needs.
     */

    // public const STATUS_PENDING  = 'pending';
    // public const STATUS_APPROVED = 'approved';
    // public const STATUS_REJECTED = 'rejected';

    // public function scopePending($query)
    // {
    //     return $query->where('status', self::STATUS_PENDING);
    // }
}
