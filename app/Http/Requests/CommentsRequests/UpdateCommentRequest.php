<?php

namespace App\Http\Requests\CommentsRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
