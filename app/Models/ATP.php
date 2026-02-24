<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @deprecated ATPs table may not exist in the 2024 restored DB.
 * Beat schedules are now managed via beat_schedules table.
 * Kept for backward compatibility.
 */
class ATP extends Model
{
    use HasFactory;

    protected $table = 'atps';

    protected $fillable = [
        'user_id',
        'plan_date',
        'month',
        'status',
        'remarks',
    ];

    protected $casts = [
        'plan_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ATPItem::class, 'atp_id');
    }
}
