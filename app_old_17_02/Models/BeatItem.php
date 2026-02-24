<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
        'quantity' => 'decimal:2',
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
