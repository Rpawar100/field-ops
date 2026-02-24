<?php

namespace App\Models;

/**
 * Territory is a backward-compatible alias that queries zrth_hierarchies where level='territory'.
 * All new code should use ZrthHierarchy::territories() scope instead.
 *
 * @deprecated Use ZrthHierarchy with scopeTerritories() instead
 */
class Territory extends ZrthHierarchy
{
    protected static function booted(): void
    {
        static::addGlobalScope('territory_level', function ($query) {
            $query->where('level', 'territory');
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->level = 'territory';
        });
    }
}
