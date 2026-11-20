<?php

namespace App\Http\Requests\Comments;

use Blaspsoft\Blasp\Enums\Severity;
use Blaspsoft\Blasp\Rules\Profanity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComment extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:2', 'max:'.config('comments.max_length'), Profanity::severity(Severity::Extreme)],
        ];
    }
}
