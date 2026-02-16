<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserZrthAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zone_id',
        'region_id',
        'territory_id',
        'headquarters_id',
        'assignment_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'assignment_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function headquarters(): BelongsTo
    {
        return $this->belongsTo(Headquarters::class);
    }
}
