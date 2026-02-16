<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'taluka_id',
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function taluka(): BelongsTo
    {
        return $this->belongsTo(Taluka::class);
    }

    public function farmers(): HasMany
    {
        return $this->hasMany(Farmer::class);
    }

    public function retailers(): HasMany
    {
        return $this->hasMany(Retailer::class);
    }

    public function beats(): HasMany
    {
        return $this->hasMany(Beat::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'location_id');
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }
}
