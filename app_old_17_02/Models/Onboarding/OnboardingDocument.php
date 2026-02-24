<?php

namespace App\Models\Onboarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'onboarding_request_id',
        'document_type',
        'document_name',
        'file_path',
        'uploaded_at',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function onboardingRequest(): BelongsTo
    {
        return $this->belongsTo(OnboardingRequest::class);
    }
}
