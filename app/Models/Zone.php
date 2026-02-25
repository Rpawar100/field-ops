<?php

namespace App\Models;

/**
 * Zone is a backward-compatible alias that queries zrth_hierarchies where level='zone'.
 * All new code should use ZrthHierarchy::zones() scope instead.
 *
 * @deprecated Use ZrthHierarchy with scopeZones() instead
 */
class Zone extends ZrthHierarchy
{
    protected static function booted(): void
    {
        static::addGlobalScope('zone_level', function ($query) {
            $query->where('level', 'zone');
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->level = 'zone';
        });
    }
}
