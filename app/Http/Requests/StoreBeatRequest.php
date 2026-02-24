<?php

namespace App\Http\Requests;

use App\Models\Beat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('beat:manage');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:beats,code',
            'user_id' => 'nullable|exists:users,id',
            'zrth_node_id' => 'required|exists:zrth_nodes,id',
            'sdtv_node_id' => 'required|exists:sdtv_nodes,id',
            'day_of_week' => [
                'nullable',
                Rule::in(Beat::DAYS),
            ],
            'visit_frequency' => 'nullable|integer|min:1|max:30',
            'farmer_ids' => 'nullable|array',
            'farmer_ids.*' => 'exists:farmers,id',
            'retailer_ids' => 'nullable|array',
            'retailer_ids.*' => 'exists:retailers,id',
        ];
    }
}
