<?php

namespace App\Models;

/**
 * Region is a backward-compatible alias that queries zrth_hierarchies where level='region'.
 * All new code should use ZrthHierarchy::regions() scope instead.
 *
 * @deprecated Use ZrthHierarchy with scopeRegions() instead
 */
class Region extends ZrthHierarchy
{
    protected static function booted(): void
    {
        static::addGlobalScope('region_level', function ($query) {
            $query->where('level', 'region');
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->level = 'region';
        });
    }
}
