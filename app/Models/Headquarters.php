<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Headquarters extends Model
{
    use HasFactory;

    protected $fillable = [
        'territory_id',
        'name',
        'code',
        'address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }
}
