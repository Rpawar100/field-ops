<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoStagePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'demo_stage_activity_id',
        'photo_path',
        'photo_type',
        'caption',
        'sort_order',
        'latitude',
        'longitude',
        'taken_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'taken_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function demoStageActivity(): BelongsTo
    {
        return $this->belongsTo(DemoStageActivity::class);
    }
}
