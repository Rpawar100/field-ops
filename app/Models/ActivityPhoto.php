<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'photo_path',
        'photo_type',
        'caption',
        'sort_order',
        'latitude',
        'longitude',
        'taken_at',
        'file_size',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'taken_at' => 'datetime',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
