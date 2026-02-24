<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class BeatSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'beat_id',
        'user_id',
        'scheduled_date',
        'status',
        'attendance_id',
        'remarks',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    // ==================== SCOPES ====================

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('scheduled_date', $date);
    }
}
