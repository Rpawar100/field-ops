<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string', // Can be email, phone, or employee_code
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
            'firebase_token' => 'nullable|string',
            'revoke_existing' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email, phone, or employee code.',
            'password.required' => 'Please enter your password.',
        ];
    }
}
