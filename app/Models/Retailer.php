<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Retailer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'retailer_code',
        'shop_name',
        'owner_name',
        'contact_person',
        'mobile',
        'alternate_mobile',
        'whatsapp_number',
        'email',
        'business_type',
        'firm_name',
        'address',
        'village_id',
        'pincode',
        'latitude',
        'longitude',
        'landmark',
        'zrth_hierarchy_id',
        'beat_id',
        'aadhaar_number',
        'aadhaar_document',
        'pan_number',
        'pan_document',
        'gst_number',
        'gst_document',
        'fssai_number',
        'fssai_document',
        'pesticide_license_number',
        'pesticide_license_document',
        'pesticide_license_expiry',
        'seed_license_number',
        'seed_license_document',
        'seed_license_expiry',
        'fertilizer_license_number',
        'fertilizer_license_document',
        'fertilizer_license_expiry',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'bank_ifsc_code',
        'annual_business_value',
        'monthly_potential',
        'retailer_grade',
        'product_categories_dealt',
        'shop_photo',
        'shop_interior_photo',
        'owner_photo',
        'kyc_status',
        'verified_by',
        'verified_at',
        'verification_remarks',
        'registered_by',
        'status',
        'sync_status',
        'synced_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'pesticide_license_expiry' => 'date',
        'seed_license_expiry' => 'date',
        'fertilizer_license_expiry' => 'date',
        'annual_business_value' => 'decimal:2',
        'monthly_potential' => 'decimal:2',
        'product_categories_dealt' => 'json',
        'verified_at' => 'datetime',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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

    public function distributors(): BelongsToMany
    {
        return $this->belongsToMany(Distributor::class, 'retailer_distributors', 'retailer_id', 'distributor_id')
                    ->withPivot('is_primary', 'credit_limit', 'credit_days', 'status')
                    ->withTimestamps();
    }

    public function retailerDistributors(): HasMany
    {
        return $this->hasMany(RetailerDistributor::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RetailerDocument::class);
    }

    public function farmers(): BelongsToMany
    {
        return $this->belongsToMany(Farmer::class, 'farmer_retailers', 'retailer_id', 'farmer_id')
                    ->withPivot('is_primary', 'annual_purchase_value')
                    ->withTimestamps();
    }

    public function farmerRetailers(): HasMany
    {
        return $this->hasMany(FarmerRetailer::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function beatRetailers(): HasMany
    {
        return $this->hasMany(BeatRetailer::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByGrade(Builder $query, string $grade): Builder
    {
        return $query->where('retailer_grade', $grade);
    }
}
