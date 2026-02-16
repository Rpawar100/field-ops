<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class DemoDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'demo_lot_id',
        'from_user_id',
        'to_user_id',
        'distribution_date',
        'quantity_distributed',
        'remarks',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'distribution_date' => 'date',
        'quantity_distributed' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function demoLot(): BelongsTo
    {
        return $this->belongsTo(DemoLot::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
