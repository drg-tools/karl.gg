<?php

namespace Hazzard\Comments\Http\Requests;

use Hazzard\Comments\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreComment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return config('comments.allow_guests') || ! is_null($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->user()
            ? $this->authenticatedRules()
            : $this->guestRules();

        return $this->baseRules() + $rules;
    }

    /**
     * Get the base validation rules.
     *
     * @return array
     */
    protected function baseRules()
    {
        $table = config('comments.table_names.comments');

        $rules = [
            'content' => 'required|min:2',
            'root_id' => "nullable|exists:$table,id,parent_id,NULL",
            'parent_id' => "nullable|required_with:root_id|exists:$table,id",
            'permalink' => 'nullable|url',
        ];

        if ($this->has('page_id')) {
            $rules['page_id'] = 'required';
        } else {
            $rules['commentable_id'] = 'required|numeric';
            $rules['commentable_type'] = 'required|string';
        }

        if (! $this->user() || ! $this->user()->can('moderate', Comment::class)) {
            $rules['parent_id'] .= ',status,approved';
        }

        return $rules;
    }

    /**
     * Get the validation rules for authenticated users.
     *
     * @return array
     */
    protected function authenticatedRules()
    {
        return config('comments.captcha_auth') ? [
            'captcha' => 'required|comment_captcha',
        ] : [];
    }

    /**
     * Get the validation rules for guest users.
     *
     * @return array
     */
    protected function guestRules()
    {
        $rules = [
            'author_name' => 'required|max:100',
            'author_email' => 'required|email|max:200',
            'author_url' => 'nullable|url|max:200',
        ];

        if (config('comments.captcha_guest')) {
            $rules['captcha'] = 'required|comment_captcha';
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasDuplicateContent()) {
                $validator->errors()->add('content', trans('comments::messages.duplicate'));
            }

            if ($this->hasTooManyUnapproved()) {
                $validator->errors()->add('content', trans('comments::messages::max_unapproved'));
            }

            if (! $this->page_id && ! $this->commentableExists()) {
                $validator->errors()->add('commentable_id', trans('validation.exists', [
                    'attribute' => 'commentable_id',
                ]));
            }

            $max = config('comments.max_length');
            if ($max && mb_strlen($this->get('content')) > $max) {
                $validator->errors()->add('content', trans('validation.max.string', [
                    'max' => $max, 'attribute' => 'content',
                ]));
            }
        });
    }

    /**
     * Determine if the user has too many unapproved comments.
     *
     * @return bool
     */
    protected function hasTooManyUnapproved()
    {
        if (! $max = config('comments.max_unapproved')) {
            return false;
        }

        $pending = app('comments.repository')->getUnapprovedCount(
            $this->user() ? $this->user()->getKey() : $this->author_email
        );

        return $pending >= $max;
    }

    /**
     * Determine if another comment with the same content and for the same page exists.
     *
     * @return bool
     */
    public function hasDuplicateContent()
    {
        if (! config('comments.detect_duplicates')) {
            return false;
        }

        return app('comments.repository')->hasDuplicateContent(array_merge(
            $this->only('content', 'page_id', 'commentable_id', 'commentable_type'),
            ['user' => $this->user() ? $this->user()->getKey() : $this->author_email]
        ));
    }

    /**
     * Determine if the commentable entity exists.
     *
     * @return bool
     */
    protected function commentableExists()
    {
        $id = $this->commentable_id;
        $type = str_replace('.', '\\', $this->commentable_type);

        if (! class_exists($type)) {
            return false;
        }

        $model = new $type;

        return ! is_null($model->find($id, [$model->getKeyName()]));
    }
}
