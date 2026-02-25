<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRetailerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('retailer:create');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:retailers,phone',
            'alternate_phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'sdtv_node_id' => 'required|exists:sdtv_nodes,id',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'gst_number' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'shop_photo' => 'nullable|string',
            'beat_id' => 'nullable|exists:beats,id',
            'distributor_ids' => 'nullable|array',
            'distributor_ids.*' => 'exists:distributors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'A retailer with this phone number already exists.',
            'sdtv_node_id.required' => 'Please select a location.',
        ];
    }
}
