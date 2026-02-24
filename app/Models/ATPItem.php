<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @deprecated See ATP model. Kept for backward compatibility.
 */
class ATPItem extends Model
{
    use HasFactory;

    protected $table = 'atp_items';

    protected $fillable = [
        'atp_id',
        'beat_id',
        'planned_time',
        'description',
        'status',
    ];

    protected $casts = [
        'planned_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function atp(): BelongsTo
    {
        return $this->belongsTo(ATP::class, 'atp_id');
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }
}
