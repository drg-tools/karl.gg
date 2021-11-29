<?php

namespace Hazzard\Comments\Http\Requests;

use Hazzard\Comments\Comment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ['content' => 'min:2'];

        if (! $this->user()->can('moderate', Comment::class)) {
            return $rules;
        }

        $comment = app('comments.repository')->find($this->route('comment'));

        if (empty($comment->user_id)) {
            $rules += [
                'author_name' => 'max:100',
                'author_email' => 'email|max:200',
                'author_url' => 'nullable|url|max:200',
            ];
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
            $max = config('comments.max_length');
            if ($max && mb_strlen($this->get('content')) > $max) {
                $validator->errors()->add('content', trans('validation.max.string', [
                    'max' => $max, 'attribute' => 'content',
                ]));
            }
        });
    }
}
