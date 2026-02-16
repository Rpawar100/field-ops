<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ATPItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'atp_id',
        'beat_id',
        'planned_time',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'planned_time' => 'datetime',
    ];

    public function atp(): BelongsTo
    {
        return $this->belongsTo(ATP::class);
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }
}
