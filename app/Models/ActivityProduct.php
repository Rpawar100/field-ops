<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'product_id',
        'quantity',
        'quantity_unit',
        'action',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
