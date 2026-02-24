<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'holiday_date',
        'type',
        'zrth_hierarchy_id',
        'is_optional',
    ];

    protected $casts = [
        'holiday_date' => 'date',
        'is_optional' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Type constants
    public const TYPE_NATIONAL = 'national';
    public const TYPE_REGIONAL = 'regional';
    public const TYPE_COMPANY = 'company';

    // ==================== RELATIONSHIPS ====================

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }

    // ==================== SCOPES ====================

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('holiday_date', $date);
    }

    public function scopeNational(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_NATIONAL);
    }
}
