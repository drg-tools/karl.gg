<?php

namespace App\Http\Requests\Comments;

use App\Comment;
use App\Comments\CommentRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;

class StoreComment extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'page_id' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:2', 'max:'.config('comments.max_length')],
            'root_id' => ['nullable', Rule::exists('comments', 'id')->whereNull('parent_id')],
            'parent_id' => ['nullable', 'required_with:root_id', $this->parentRule()],
            'permalink' => ['nullable', 'url'],
        ];

        if (! $this->user()) {
            $rules += [
                'author_name' => ['required', 'max:100'],
                'author_email' => ['required', 'email', 'max:200'],
                'author_url' => ['nullable', 'url', 'max:200'],
            ];
        }

        return $rules;
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $author = $this->user()?->getKey() ?? $this->input('author_email');

                if ($author && app(CommentRepository::class)->unapprovedCount($author) >= config('comments.max_unapproved')) {
                    $validator->errors()->add('content', trans('comments-messages.max_unapproved'));
                }
            },
        ];
    }

    /**
     * Only moderators may reply to comments that are not approved.
     */
    protected function parentRule(): Exists
    {
        $rule = Rule::exists('comments', 'id');

        if (! $this->user()?->can('moderate', Comment::class)) {
            $rule->where('status', Comment::STATUS_APPROVED);
        }

        return $rule;
    }
}
