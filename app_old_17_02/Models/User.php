<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'reporting_manager_id',
        'designation',
        'employee_code',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    // Self-referencing relationship for reporting manager
    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    // Subordinates who report to this user
    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'reporting_manager_id');
    }

    // Activities executed by this user
    public function executedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'executed_by');
    }

    // Activities attended by this user (many-to-many)
    public function attendedActivities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'attended_users', 'user_id', 'activity_id')
                    ->withTimestamps();
    }

    // Roles assigned to this user
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
                    ->withTimestamps();
    }

    // ZRTH Assignments
    public function zrthAssignments(): HasMany
    {
        return $this->hasMany(UserZrthAssignment::class);
    }

    // Attendance records
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ATPs (Activity Timing Plans)
    public function atps(): HasMany
    {
        return $this->hasMany(ATP::class);
    }

    // Demo Distributions from this user
    public function demoDistributionsAsFrom(): HasMany
    {
        return $this->hasMany(DemoDistribution::class, 'from_user_id');
    }

    // Demo Distributions to this user
    public function demoDistributionsAsTo(): HasMany
    {
        return $this->hasMany(DemoDistribution::class, 'to_user_id');
    }

    // Demo Executions assigned to this user
    public function assignedDemoExecutions(): HasMany
    {
        return $this->hasMany(DemoExecution::class, 'assigned_to_user_id');
    }

    // Demo Executions inspected by this user
    public function inspectedDemoExecutions(): HasMany
    {
        return $this->hasMany(DemoExecution::class, 'inspector_user_id');
    }

    // Notifications for this user
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Audit logs for actions by this user
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'performed_by');
    }

    // Onboarding requests by this user
    public function onboardingRequests(): HasMany
    {
        return $this->hasMany(OnboardingRequest::class, 'requested_by');
    }

    // Onboarding approvals by this user
    public function approvingOnboardingRequests(): HasMany
    {
        return $this->hasMany(OnboardingRequest::class, 'approved_by');
    }
}
