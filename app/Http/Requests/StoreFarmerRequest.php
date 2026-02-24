<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('farmer:create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:farmers,phone',
            'alternate_phone' => 'nullable|string|max:15',
            'sdtv_node_id' => 'required|exists:sdtv_nodes,id',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'land_holding_acres' => 'nullable|numeric|min:0',
            'crops_grown' => 'nullable|string|max:500', // Comma-separated
            'preferred_retailer_id' => 'nullable|exists:retailers,id',
            'reference_farmer_id' => 'nullable|exists:farmers,id',
            'profile_photo' => 'nullable|string',
            'beat_id' => 'nullable|exists:beats,id',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'A farmer with this phone number already exists.',
            'sdtv_node_id.required' => 'Please select a village.',
        ];
    }
}
