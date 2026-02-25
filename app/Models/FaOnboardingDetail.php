<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaOnboardingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pan_number',
        'pan_document',
        'aadhaar_number',
        'aadhaar_document',
        'driving_license_number',
        'driving_license_document',
        'qualification',
        'qualification_document',
        'experience_details',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'bank_ifsc_code',
        'cancelled_cheque',
        'proposed_salary',
        'approved_salary',
        'salary_status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'kyc_status',
        'verified_by',
        'verified_at',
        'verification_remarks',
    ];

    protected $casts = [
        'proposed_salary' => 'decimal:2',
        'approved_salary' => 'decimal:2',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Documents uploaded as part of onboarding.
     */
    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FaDocument::class, 'user_id', 'user_id');
    }
}
