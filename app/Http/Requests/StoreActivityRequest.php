<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('activity:create');
    }

    public function rules(): array
    {
        return [
            'activity_type_id' => 'required|exists:activity_types,id',
            'target_type' => 'required|in:farmer,retailer',
            'target_id' => 'required|integer',
            'product_id' => 'nullable|exists:products,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|string', // Base64 or URL
            'notes' => 'nullable|string|max:1000',
            'metadata' => 'nullable|array',
            'local_id' => 'nullable|string|max:100', // For offline sync
            'performed_at' => 'nullable|date',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate target exists
            $targetType = $this->target_type;
            $targetId = $this->target_id;

            if ($targetType === 'farmer') {
                $exists = \App\Models\Farmer::where('id', $targetId)->exists();
            } else {
                $exists = \App\Models\Retailer::where('id', $targetId)->exists();
            }

            if (!$exists) {
                $validator->errors()->add('target_id', "The specified {$targetType} does not exist.");
            }
        });
    }
}
