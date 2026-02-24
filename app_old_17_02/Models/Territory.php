<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Territory extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
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

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function headquarters(): HasMany
    {
        return $this->hasMany(Headquarters::class);
    }

    public function beats(): HasMany
    {
        return $this->hasMany(Beat::class);
    }
}
