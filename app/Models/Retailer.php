<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_id',
        'taluka_id',
        'district_id',
        'state_id',
        'beat_id',
        'name',
        'shop_name',
        'contact_number',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function taluka(): BelongsTo
    {
        return $this->belongsTo(Taluka::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function farmerRetailers(): HasMany
    {
        return $this->hasMany(FarmerRetailer::class);
    }
}
