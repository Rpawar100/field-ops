<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRetailerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('retailer:update');
    }

    public function rules(): array
    {
        $retailerId = $this->route('id');

        return [
            'name' => 'sometimes|required|string|max:255',
            'shop_name' => 'sometimes|required|string|max:255',
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:15',
                Rule::unique('retailers', 'phone')->ignore($retailerId),
            ],
            'alternate_phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'sdtv_node_id' => 'sometimes|required|exists:sdtv_nodes,id',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'gst_number' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'shop_photo' => 'nullable|string',
            'beat_ids' => 'nullable|array',
            'beat_ids.*' => 'exists:beats,id',
            'distributor_ids' => 'nullable|array',
            'distributor_ids.*' => 'exists:distributors,id',
            'is_active' => 'nullable|boolean',
        ];
    }
}
