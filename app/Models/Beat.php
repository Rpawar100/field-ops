<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beat extends Model
{
    use HasFactory;

    protected $fillable = [
        'territory_id',
        'headquarters_id',
        'village_id',
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

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function headquarters(): BelongsTo
    {
        return $this->belongsTo(Headquarters::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function beatItems(): HasMany
    {
        return $this->hasMany(BeatItem::class);
    }

    public function atpItems(): HasMany
    {
        return $this->hasMany(ATPItem::class);
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }
}
