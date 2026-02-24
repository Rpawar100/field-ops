<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @deprecated The 2024 schema uses beat_farmers/beat_retailers pivot tables instead.
 * Kept for backward compatibility.
 */
class BeatItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'beat_id',
        'product_id',
        'quantity',
        'unit_of_measure',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
