<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'executed_by',
        'activity_type',
        'title',
        'description',
        'location_id',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
    ];

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'location_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ActivityAttribute::class);
    }

    public function attendedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attended_users', 'activity_id', 'user_id')
                    ->withTimestamps();
    }
}
