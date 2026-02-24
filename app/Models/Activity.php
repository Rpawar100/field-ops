<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'activity_type_id',
        'activity_master_id',
        'farmer_id',
        'retailer_id',
        'distributor_id',
        'beat_id',
        'village_id',
        'zrth_hierarchy_id',
        'execution_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'attributes',
        'products',
        'gps_latitude',
        'gps_longitude',
        'gps_accuracy',
        'address',
        'photos',
        'voice_note_path',
        'voice_note_duration',
        'remarks',
        'next_action',
        'next_visit_date',
        'attendance_id',
        'demo_farmer_assignment_id',
        'demo_stage_activity_id',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_remarks',
        'sync_status',
        'synced_at',
        'sync_attempts',
        'sync_error',
        'device_id',
        'app_version',
        'status',
    ];

    protected $casts = [
        'execution_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'attributes' => 'json',
        'products' => 'json',
        'gps_latitude' => 'decimal:8',
        'gps_longitude' => 'decimal:8',
        'gps_accuracy' => 'decimal:2',
        'photos' => 'json',
        'voice_note_duration' => 'integer',
        'next_visit_date' => 'date',
        'approved_at' => 'datetime',
        'synced_at' => 'datetime',
        'sync_attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function activityMaster(): BelongsTo
    {
        return $this->belongsTo(ActivityMaster::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    public function demoFarmerAssignment(): BelongsTo
    {
        return $this->belongsTo(DemoFarmerAssignment::class);
    }

    public function demoStageActivity(): BelongsTo
    {
        return $this->belongsTo(DemoStageActivity::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function activityPhotos(): HasMany
    {
        return $this->hasMany(ActivityPhoto::class);
    }

    public function activityProducts(): HasMany
    {
        return $this->hasMany(ActivityProduct::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ActivityComment::class);
    }

    public function attendedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attended_users', 'activity_id', 'user_id')
                    ->withPivot('role_in_activity')
                    ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('execution_date', $date);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
