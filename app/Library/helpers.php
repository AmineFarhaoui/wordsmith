<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('array_keys_prefix')) {
    /**
     * Add a prefix to array keys.
     */
    function array_keys_prefix(array $array, string $prefix): array
    {
        $prefixedArray = [];

        foreach ($array as $key => $value) {
            $prefixedArray[$prefix.$key] = $value;
        }

        return $prefixedArray;
    }
}

if (! function_exists('array_keys_convert_case')) {
    /**
     * Converts the keys of an array to the specified case.
     *
     * @param  'camel'|'kebab'|'snake'|'studly'  $case
     *
     * @throws \Exception
     */
    function array_keys_convert_case(array $array, string $case): array
    {
        $cases = ['camel', 'kebab', 'snake', 'studly'];

        throw_if(
            ! in_array($case, $cases),
            Exception::class,
            "Case \"$case\" not supported",
        );

        $converted = [];

        foreach ($array as $key => $value) {
            $converted[Str::{$case}($key)] = is_array($value)
                ? array_keys_convert_case($value, $case)
                : $value;
        }

        return $converted;
    }
}

if (! function_exists('current_user')) {
    /**
     * Returns the current user.
     *
     * @return null|\App\Models\User
     */
    function current_user()
    {
        return Auth::user();
    }
}

if (! function_exists('data_get_multiple')) {
    /**
     * Get items from an array or object using "dot" notation.
     *
     * @param  array  $keys
     */
    function data_get_multiple($target, $keys, $default = null)
    {
        $results = [];

        foreach ($keys as $index => $key) {
            $results[] = data_get($target, $key, $default);
        }

        return $results;
    }
}

if (! function_exists('data_map')) {
    /**
     * Maps a source of data according to the specified mapping.
     */
    function data_map(array $data, array $mappings): array
    {
        $mapped = [];

        foreach ($mappings as $source => $target) {
            data_set($mapped, $target, data_get($data, $source));
        }

        return $mapped;
    }
}
