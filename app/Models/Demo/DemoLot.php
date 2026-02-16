<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemoLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_number',
        'description',
        'quantity',
        'unit_of_measure',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'quantity' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function distributions(): HasMany
    {
        return $this->hasMany(DemoDistribution::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(DemoExecution::class);
    }
}
