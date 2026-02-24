<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_selfie_path',
        'check_in_address',
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_selfie_path',
        'check_out_address',
        'attendance_type',
        'joint_work_with_user_id',
        'leave_request_id',
        'is_valid',
        'activity_count',
        'minimum_activity_required',
        'total_working_minutes',
        'distance_covered_km',
        'remarks',
        'manager_remarks',
        'sync_status',
        'synced_at',
        'sync_attempts',
        'sync_error',
        'device_id',
        'device_model',
        'app_version',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_time' => 'datetime',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'is_valid' => 'boolean',
        'activity_count' => 'integer',
        'minimum_activity_required' => 'integer',
        'total_working_minutes' => 'integer',
        'distance_covered_km' => 'decimal:2',
        'synced_at' => 'datetime',
        'sync_attempts' => 'integer',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jointWorkUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'joint_work_with_user_id');
    }

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function locationTrails(): HasMany
    {
        return $this->hasMany(AttendanceLocationTrail::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function beatSchedules(): HasMany
    {
        return $this->hasMany(BeatSchedule::class);
    }

    public function dailyActivitySummary(): HasMany
    {
        return $this->hasMany(DailyActivitySummary::class);
    }

    // ==================== SCOPES ====================

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('is_valid', true);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
