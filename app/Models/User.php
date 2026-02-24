<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'email',
        'password',
        'role',
        'designation',
        'fa_type',
        'reporting_manager_id',
        'status',
        'app_access_enabled',
        'date_of_joining',
        'date_of_leaving',
        'profile_photo',
        'address',
        'device_token',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_of_joining' => 'date',
        'date_of_leaving' => 'date',
        'app_access_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Role constants
    public const ROLE_NSM = 'nsm';
    public const ROLE_ZM = 'zm';
    public const ROLE_RM = 'rm';
    public const ROLE_TSL = 'tsl';
    public const ROLE_FA = 'fa';

    public const ROLES = [
        self::ROLE_NSM,
        self::ROLE_ZM,
        self::ROLE_RM,
        self::ROLE_TSL,
        self::ROLE_FA,
    ];

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUSPENDED = 'suspended';

    // ==================== RELATIONSHIPS ====================

    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'reporting_manager_id');
    }

    /**
     * ZRTH assignments via pivot table
     */
    public function zrthAssignments(): HasMany
    {
        return $this->hasMany(UserZrthAssignment::class);
    }

    public function zrthHierarchies(): BelongsToMany
    {
        return $this->belongsToMany(ZrthHierarchy::class, 'user_zrth_assignments', 'user_id', 'zrth_hierarchy_id')
                    ->withPivot('is_primary', 'assigned_from', 'assigned_to', 'status')
                    ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function attendedActivities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'attended_users', 'user_id', 'activity_id')
                    ->withPivot('role_in_activity')
                    ->withTimestamps();
    }

    public function beatAssignments(): HasMany
    {
        return $this->hasMany(BeatUserAssignment::class);
    }

    public function beatSchedules(): HasMany
    {
        return $this->hasMany(BeatSchedule::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function faOnboardingDetail(): HasOne
    {
        return $this->hasOne(FaOnboardingDetail::class);
    }

    public function faDocuments(): HasMany
    {
        return $this->hasMany(FaDocument::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function dailyActivitySummaries(): HasMany
    {
        return $this->hasMany(DailyActivitySummary::class);
    }

    public function syncQueues(): HasMany
    {
        return $this->hasMany(SyncQueue::class);
    }

    public function demoDistributionsFrom(): HasMany
    {
        return $this->hasMany(DemoDistribution::class, 'from_user_id');
    }

    public function demoDistributionsTo(): HasMany
    {
        return $this->hasMany(DemoDistribution::class, 'to_user_id');
    }

    public function farmerVisitHistory(): HasMany
    {
        return $this->hasMany(FarmerVisitHistory::class, 'visited_by');
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeFieldAssistants(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_FA);
    }
}
