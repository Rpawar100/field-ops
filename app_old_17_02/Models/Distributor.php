<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_id',
        'beat_id',
        'name',
        'contact_number',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class);
    }
}
