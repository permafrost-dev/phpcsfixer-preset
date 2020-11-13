<?php

namespace Permafrost\PhpCsFixerRules\Support;

class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter Default to underscore
     *
     * @return string
     */
    public static function snake(string $value, ?string $delimiter = null): string
    {
        if (!\ctype_lower($value)) {
            $value = (string)\preg_replace('/\s+/u', '', \ucwords($value));
            $value = (string)\mb_strtolower(\preg_replace(
                '/(.)(?=[A-Z])/u',
                '$1' . ($delimiter ?? '_'),
                $value
            ));
        }

        return $value;
    }

    /**
     * Convert a string to StudlyCase.
     *
     * @param string $value
     *
     * @return string
     */
    public static function studly(string $value): string
    {
        return ucwords(preg_replace(['~[_\-]~', '~\s+~'], [' ', ''], $value));
    }
}
