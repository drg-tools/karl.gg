<?php

namespace Tests\Feature\Comments;

use App\Comments\Formatter;

/**
 * Stores and renders comment text unchanged, so API tests can assert on plain strings.
 */
class FormatterFake extends Formatter
{
    public function parse(string $text): string
    {
        return $text;
    }

    public function unparse(string $xml): string
    {
        return $xml;
    }

    public function render(string $xml): string
    {
        return $xml;
    }
}
