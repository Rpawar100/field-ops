<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * ZRTH Hierarchy — single self-referencing table for Zone/Region/Territory/HQ
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $level  zone|region|territory|hq
 * @property int|null $parent_id
 * @property string $status  active|inactive
 * @property string|null $description
 */
class ZrthHierarchy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zrth_hierarchies';

    protected $fillable = [
        'code',
        'name',
        'level',
        'parent_id',
        'status',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Level constants
    public const LEVEL_ZONE = 'zone';
    public const LEVEL_REGION = 'region';
    public const LEVEL_TERRITORY = 'territory';
    public const LEVEL_HQ = 'hq';

    public const LEVELS = [
        self::LEVEL_ZONE,
        self::LEVEL_REGION,
        self::LEVEL_TERRITORY,
        self::LEVEL_HQ,
    ];

    public const LEVEL_HIERARCHY = [
        self::LEVEL_ZONE => 1,
        self::LEVEL_REGION => 2,
        self::LEVEL_TERRITORY => 3,
        self::LEVEL_HQ => 4,
    ];

    // ==================== RELATIONSHIPS ====================

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Users assigned via user_zrth_assignments pivot
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_zrth_assignments', 'zrth_hierarchy_id', 'user_id')
                    ->withPivot('is_primary', 'assigned_from', 'assigned_to', 'status')
                    ->withTimestamps();
    }

    public function userAssignments(): HasMany
    {
        return $this->hasMany(UserZrthAssignment::class, 'zrth_hierarchy_id');
    }

    public function beats(): HasMany
    {
        return $this->hasMany(Beat::class, 'zrth_hierarchy_id');
    }

    /**
     * Villages mapped via sdtv_zrth_mappings pivot
     */
    public function villages(): BelongsToMany
    {
        return $this->belongsToMany(Village::class, 'sdtv_zrth_mappings', 'zrth_hierarchy_id', 'village_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function sdtvMappings(): HasMany
    {
        return $this->hasMany(SdtvZrthMapping::class, 'zrth_hierarchy_id');
    }

    public function farmers(): HasMany
    {
        return $this->hasMany(Farmer::class, 'zrth_hierarchy_id');
    }

    public function retailers(): HasMany
    {
        return $this->hasMany(Retailer::class, 'zrth_hierarchy_id');
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class, 'zrth_hierarchy_id');
    }

    // ==================== SCOPES ====================

    public function scopeZones(Builder $query): Builder
    {
        return $query->where('level', self::LEVEL_ZONE);
    }

    public function scopeRegions(Builder $query): Builder
    {
        return $query->where('level', self::LEVEL_REGION);
    }

    public function scopeTerritories(Builder $query): Builder
    {
        return $query->where('level', self::LEVEL_TERRITORY);
    }

    public function scopeHeadquarters(Builder $query): Builder
    {
        return $query->where('level', self::LEVEL_HQ);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeUnderParent(Builder $query, int $parentId): Builder
    {
        return $query->where('parent_id', $parentId);
    }

    // ==================== METHODS ====================

    public function getDescendants(bool $includeSelf = false): Collection
    {
        $descendants = collect();
        if ($includeSelf) {
            $descendants->push($this);
        }
        $children = $this->children()->with('children')->get();
        foreach ($children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendants(false));
        }
        return $descendants;
    }

    public function getDescendantIds(bool $includeSelf = false): array
    {
        return $this->getDescendants($includeSelf)->pluck('id')->toArray();
    }

    public function getAncestors(bool $includeSelf = false): Collection
    {
        $ancestors = collect();
        if ($includeSelf) {
            $ancestors->push($this);
        }
        $parent = $this->parent;
        while ($parent !== null) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
        return $ancestors;
    }

    public function getAncestorIds(bool $includeSelf = false): array
    {
        return $this->getAncestors($includeSelf)->pluck('id')->toArray();
    }

    public function getFullPath(): string
    {
        $ancestors = $this->getAncestors(true)->reverse();
        return $ancestors->pluck('name')->implode(' > ');
    }

    public function isDescendantOf(int $nodeId): bool
    {
        return in_array($nodeId, $this->getAncestorIds(false));
    }

    public function isAncestorOf(int $nodeId): bool
    {
        return in_array($nodeId, $this->getDescendantIds(false));
    }

    public function getLevelOrder(): int
    {
        return self::LEVEL_HIERARCHY[$this->level] ?? 0;
    }

    public function isLowestLevel(): bool
    {
        return $this->level === self::LEVEL_HQ;
    }

    public function isHighestLevel(): bool
    {
        return $this->level === self::LEVEL_ZONE;
    }
}
