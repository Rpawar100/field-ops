<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'document_type',
        'document_name',
        'document_path',
        'document_number',
        'expiry_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }
}
