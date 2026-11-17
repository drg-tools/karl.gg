<?php

namespace App\GraphQL\Scalars;

use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

/**
 * Read more about scalars here http://webonyx.github.io/graphql-php/type-system/scalar-types/.
 */
class JSON extends ScalarType
{
    public ?string $description =
        'The `JSON` scalar type represents JSON values as specified by
        [ECMA-404](http://www.ecma-international.org/publications/files/ECMA-ST/ECMA-404.pdf).';

    /**
     * Serializes an internal value to include in a response.
     */
    public function serialize(mixed $value): mixed
    {
        // Assuming the internal representation of the value is always correct
        return json_decode($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     */
    public function parseValue(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * E.g.
     * {
     *   user(email: "user@example.com")
     * }
     *
     * @param  mixed[]|null  $variables
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        switch ($valueNode) {
            case $valueNode instanceof StringValueNode:
            case $valueNode instanceof BooleanValueNode:
                return $valueNode->value;
            case $valueNode instanceof IntValueNode:
            case $valueNode instanceof FloatValueNode:
                return floatval($valueNode->value);
            case $valueNode instanceof ObjectValueNode:
                $value = [];
                foreach ($valueNode->fields as $field) {
                    $value[$field->name->value] = $this->parseLiteral($field->value);
                }

                return $value;

            case $valueNode instanceof ListValueNode:
                return array_map([$this, 'parseLiteral'], $valueNode->values);
            default:
                return null;
        }
    }
}
