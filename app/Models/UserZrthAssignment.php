<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserZrthAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zrth_hierarchy_id',
        'is_primary',
        'assigned_from',
        'assigned_to',
        'status',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'assigned_from' => 'date',
        'assigned_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }
}
