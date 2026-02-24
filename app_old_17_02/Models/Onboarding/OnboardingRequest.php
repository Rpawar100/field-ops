<?php

namespace App\Models\Onboarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Zone;
use App\Models\Region;
use App\Models\Territory;

class OnboardingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'requested_by',
        'employee_name',
        'designation',
        'zone_id',
        'region_id',
        'territory_id',
        'approved_by',
        'approval_date',
        'request_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'request_date' => 'date',
        'approval_date' => 'date',
        'status' => 'boolean',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OnboardingDocument::class);
    }
}
