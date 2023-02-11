<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class LoadoutInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['strictly_profane'],
            'description' => ['nullable', 'strictly_profane'],
            'throwable_id' => [
                'nullable',
                Rule::exists('throwables', 'id')->where(function ($query) {
                    return $query->where('character_id', $this->arg('character_id'));
                }),
            ],
        ];
    }
}
