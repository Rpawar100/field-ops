<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AppVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'version',
        'build_number',
        'is_mandatory',
        'is_latest',
        'release_notes',
        'download_url',
        'release_date',
        'status',
    ];

    protected $casts = [
        'build_number' => 'integer',
        'is_mandatory' => 'boolean',
        'is_latest' => 'boolean',
        'release_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->where('is_latest', true);
    }

    public function scopeByPlatform(Builder $query, string $platform): Builder
    {
        return $query->where('platform', $platform);
    }
}
