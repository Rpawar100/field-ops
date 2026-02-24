<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('farmer:update');
    }

    public function rules(): array
    {
        $farmerId = $this->route('id');

        return [
            'name' => 'sometimes|required|string|max:255',
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:15',
                Rule::unique('farmers', 'phone')->ignore($farmerId),
            ],
            'alternate_phone' => 'nullable|string|max:15',
            'sdtv_node_id' => 'sometimes|required|exists:sdtv_nodes,id',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'land_holding_acres' => 'nullable|numeric|min:0',
            'crops_grown' => 'nullable|string|max:500',
            'preferred_retailer_id' => 'nullable|exists:retailers,id',
            'reference_farmer_id' => 'nullable|exists:farmers,id',
            'profile_photo' => 'nullable|string',
            'beat_ids' => 'nullable|array',
            'beat_ids.*' => 'exists:beats,id',
            'is_active' => 'nullable|boolean',
        ];
    }
}
