<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Farmer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'farmer_code',
        'name',
        'father_name',
        'mobile',
        'alternate_mobile',
        'whatsapp_number',
        'email',
        'gender',
        'date_of_birth',
        'aadhaar_number',
        'aadhaar_document',
        'address',
        'village_id',
        'pincode',
        'latitude',
        'longitude',
        'landmark',
        'zrth_hierarchy_id',
        'beat_id',
        'total_landholding_acres',
        'cultivable_land_acres',
        'landholding_type',
        'irrigation_type',
        'irrigation_sources',
        'irrigated_land_acres',
        'farmer_type',
        'farmer_category',
        'is_progressive_farmer',
        'is_kol',
        'preferred_retailer_id',
        'reference_farmer_id',
        'preferred_language',
        'sms_consent',
        'whatsapp_consent',
        'photo_path',
        'registered_by',
        'status',
        'verification_status',
        'verified_by',
        'verified_at',
        'sync_status',
        'synced_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_landholding_acres' => 'decimal:2',
        'cultivable_land_acres' => 'decimal:2',
        'irrigated_land_acres' => 'decimal:2',
        'irrigation_sources' => 'json',
        'is_progressive_farmer' => 'boolean',
        'is_kol' => 'boolean',
        'sms_consent' => 'boolean',
        'whatsapp_consent' => 'boolean',
        'verified_at' => 'datetime',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Farmer type constants
    public const TYPE_PDA = 'pda';
    public const TYPE_DEMO = 'demo';
    public const TYPE_USER = 'user';
    public const TYPE_NON_USER = 'non_user';

    // ==================== RELATIONSHIPS ====================

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }

    public function registeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function preferredRetailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class, 'preferred_retailer_id');
    }

    public function referenceFarmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'reference_farmer_id');
    }

    public function crops(): HasMany
    {
        return $this->hasMany(FarmerCrop::class);
    }

    public function retailers(): BelongsToMany
    {
        return $this->belongsToMany(Retailer::class, 'farmer_retailers', 'farmer_id', 'retailer_id')
                    ->withPivot('is_primary', 'annual_purchase_value')
                    ->withTimestamps();
    }

    public function farmerRetailers(): HasMany
    {
        return $this->hasMany(FarmerRetailer::class);
    }

    public function productUsages(): HasMany
    {
        return $this->hasMany(FarmerProductUsage::class);
    }

    public function visitHistory(): HasMany
    {
        return $this->hasMany(FarmerVisitHistory::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function demoFarmerAssignments(): HasMany
    {
        return $this->hasMany(DemoFarmerAssignment::class);
    }

    public function beatFarmers(): HasMany
    {
        return $this->hasMany(BeatFarmer::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('farmer_type', $type);
    }

    public function scopeProgressive(Builder $query): Builder
    {
        return $query->where('is_progressive_farmer', true);
    }
}
