<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Crop;

class DemoStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'stage_name',
        'stage_order',
        'description',
        'duration_days',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(DemoExecution::class);
    }
}
