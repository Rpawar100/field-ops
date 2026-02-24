<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLocationTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'latitude',
        'longitude',
        'accuracy',
        'speed',
        'address',
        'recorded_at',
        'sync_status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
