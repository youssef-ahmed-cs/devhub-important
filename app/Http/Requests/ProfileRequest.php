<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|alpha_dash|unique:users,username,' . auth()->id(),
            'bio' => 'sometimes|nullable|string',
            'email' => 'sometimes|email|max:255|unique:users,email,' . auth()->id(),
            'avatar_url' => 'sometimes|file|image|max:2048',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
