<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @deprecated The 2024 schema stores activity attributes as JSON in the activities.attributes column.
 * This model is kept for backward compatibility with old code references.
 */
class ActivityAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'attribute_name',
        'attribute_value',
        'data_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
