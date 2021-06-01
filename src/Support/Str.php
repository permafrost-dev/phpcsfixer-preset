<?php

namespace Permafrost\PhpCsFixerRules\Support;

class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string|null $delimiter Defaults to underscore
     *
     * @return string
     */
    public static function snake(string $value, ?string $delimiter = null): string
    {
        if (!\ctype_lower($value)) {
            $value = str_replace(['-', '_'], ' ', $value);
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
        $result = ucwords(str_replace(['_', '-'], ' ', $value));

        return str_replace(' ', '', $result);
    }

    /**
     * Returns true if `$value` starts with `$prefix` and `$prefix` is not an empty string.
     *
     * @param string $value
     * @param string $prefix
     *
     * @return bool
     */
    public static function startsWith(string $value, string $prefix): bool
    {
        return $prefix !== ''
            && strpos($value, $prefix) === 0;
    }

    /**
     * Returns the contents of `$value` after the last instance of substring `$after`.  If `$after` is not found,
     * the contents of `$value` are returned unchanged.
     *
     * @param string $value
     * @param string $after
     *
     * @return string
     */
    public static function afterLast(string $value, string $after): string
    {
        $offset = strrpos($value, $after);

        if ($offset === false) {
            $offset = 0;
        } else {
            $offset++;
        }

        return substr($value, $offset);
    }
}
