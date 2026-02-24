<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeatUserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'beat_id',
        'user_id',
        'is_primary',
        'assigned_from',
        'assigned_to',
        'status',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'assigned_from' => 'date',
        'assigned_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
