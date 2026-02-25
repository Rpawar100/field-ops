<?php

namespace App\Models;

/**
 * Headquarters is a backward-compatible alias that queries zrth_hierarchies where level='hq'.
 * All new code should use ZrthHierarchy::headquarters() scope instead.
 *
 * @deprecated Use ZrthHierarchy with scopeHeadquarters() instead
 */
class Headquarters extends ZrthHierarchy
{
    protected static function booted(): void
    {
        static::addGlobalScope('hq_level', function ($query) {
            $query->where('level', 'hq');
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->level = 'hq';
        });
    }
}
