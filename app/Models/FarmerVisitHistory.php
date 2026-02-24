<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerVisitHistory extends Model
{
    use HasFactory;

    protected $table = 'farmer_visit_history';

    protected $fillable = [
        'farmer_id',
        'visited_by',
        'visit_date',
        'visit_purpose',
        'notes',
        'next_action',
        'next_visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function visitedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visited_by');
    }
}
