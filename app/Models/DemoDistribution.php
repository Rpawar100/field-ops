<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoDistribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'demo_master_id',
        'from_location_type',
        'from_zrth_id',
        'from_user_id',
        'from_location_name',
        'to_location_type',
        'to_zrth_id',
        'to_user_id',
        'to_farmer_id',
        'to_location_name',
        'distribution_date',
        'quantity',
        'quantity_unit',
        'receiver_name',
        'receiver_mobile',
        'receiver_designation',
        'is_acknowledged',
        'acknowledged_at',
        'acknowledgment_signature',
        'acknowledgment_photo',
        'acknowledgment_remarks',
        'latitude',
        'longitude',
        'distributed_by',
        'challan_number',
        'remarks',
        'status',
        'sync_status',
        'synced_at',
    ];

    protected $casts = [
        'distribution_date' => 'date',
        'quantity' => 'decimal:2',
        'is_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function demoMaster(): BelongsTo
    {
        return $this->belongsTo(DemoMaster::class);
    }

    public function fromZrth(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'from_zrth_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toZrth(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'to_zrth_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function toFarmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'to_farmer_id');
    }

    public function distributedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }

    public function farmerAssignments(): HasMany
    {
        return $this->hasMany(DemoFarmerAssignment::class);
    }

    // ==================== SCOPES ====================

    public function scopeAcknowledged(Builder $query): Builder
    {
        return $query->where('is_acknowledged', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
