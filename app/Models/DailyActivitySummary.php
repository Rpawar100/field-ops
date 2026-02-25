<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DailyActivitySummary extends Model
{
    use HasFactory;

    protected $table = 'daily_activity_summaries';

    protected $fillable = [
        'user_id',
        'summary_date',
        'total_activities',
        'farmer_activities',
        'retailer_activities',
        'demo_activities',
        'psa_activities',
        'other_activities',
        'total_points',
        'villages_covered',
        'beats_covered',
        'distance_covered_km',
        'productive_minutes',
        'attendance_id',
        'attendance_valid',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'total_activities' => 'integer',
        'farmer_activities' => 'integer',
        'retailer_activities' => 'integer',
        'demo_activities' => 'integer',
        'psa_activities' => 'integer',
        'other_activities' => 'integer',
        'total_points' => 'integer',
        'villages_covered' => 'integer',
        'beats_covered' => 'integer',
        'distance_covered_km' => 'decimal:2',
        'productive_minutes' => 'integer',
        'attendance_valid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

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
        return $query->whereDate('summary_date', $date);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
