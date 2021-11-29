<?php

namespace Hazzard\Comments\Contracts;

interface Formatter
{
    /**
     * Parse the text into an XML document.
     *
     * @param  string $text
     * @return string
     */
    public function parse($text);

    /**
     * Transform the XML document back into plain text.
     *
     * @param  string $xml
     * @return string
     */
    public function unparse($xml);

    /**
     * Transform the XML document into HTML.
     *
     * @param  string $xml
     * @return string
     */
    public function render($xml);
}
