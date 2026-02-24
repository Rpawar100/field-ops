<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Territory;

class DemoExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'demo_lot_id',
        'farmer_id',
        'demo_stage_id',
        'territory_id',
        'assigned_to_user_id',
        'inspector_user_id',
        'execution_date',
        'quantity_used',
        'result_notes',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'execution_date' => 'date',
        'quantity_used' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function demoLot(): BelongsTo
    {
        return $this->belongsTo(DemoLot::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function demoStage(): BelongsTo
    {
        return $this->belongsTo(DemoStage::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_user_id');
    }
}
