<?php

namespace App\Http\Requests\Comments;

use App\Comments\CommentRepository;
use App\Models\Comment;
use Blaspsoft\Blasp\Enums\Severity;
use Blaspsoft\Blasp\Rules\Profanity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreComment extends FormRequest
{
    private ?int $replyRootId = null;

    public function replyRootId(): ?int
    {
        return $this->replyRootId;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'page_id' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:2', 'max:'.config('comments.max_length'), Profanity::severity(Severity::Extreme)],
            'root_id' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'required_with:root_id', 'integer'],
            'permalink' => ['nullable', 'url'],
        ];

        if (! $this->user()) {
            $rules += [
                'author_name' => ['required', 'max:100', Profanity::severity(Severity::Extreme)],
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
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->validateThread($validator);

                $author = $this->user()?->getKey();

                if ($author && app(CommentRepository::class)->unapprovedCount($author) >= config('comments.max_unapproved')) {
                    $validator->errors()->add('content', trans('comments-messages.max_unapproved'));
                }
            },
        ];
    }

    private function validateThread(Validator $validator): void
    {
        if (! $this->filled('parent_id')) {
            return;
        }

        $pageComments = Comment::query()->where('page_id', $this->string('page_id')->value());
        $parent = (clone $pageComments)->find($this->integer('parent_id'));

        if (! $parent) {
            $validator->errors()->add('parent_id', __('validation.exists', ['attribute' => 'parent id']));

            return;
        }

        $root = $parent->parent_id === null
            ? $parent
            : (clone $pageComments)->whereNull('parent_id')->find($parent->root_id);

        if (! $root || (! $this->user()?->can('moderate', Comment::class) && (! $parent->approved() || ! $root->approved()))) {
            $validator->errors()->add('parent_id', __('validation.exists', ['attribute' => 'parent id']));

            return;
        }

        if ($this->filled('root_id') && $this->integer('root_id') !== $root->id) {
            $validator->errors()->add('root_id', __('validation.exists', ['attribute' => 'root id']));

            return;
        }

        $this->replyRootId = $root->id;
    }
}
