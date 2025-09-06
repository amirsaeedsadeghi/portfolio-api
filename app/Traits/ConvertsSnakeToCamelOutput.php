<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait ConvertsSnakeToCamelOutput
 *
 * Ensures that all array keys in the serialized output
 * are converted from snake_case to camelCase.
 *
 * This trait is typically applied to API Resources (e.g. Laravel's JsonResource)
 * so that the API responses follow a camelCase convention, even though
 * database columns and model attributes may be stored in snake_case.
 *
 * ### Responsibilities:
 * - Recursively transform array keys from snake_case → camelCase.
 * - Hook into the `resolve()` method of the parent class (usually JsonResource).
 *
 * ### Example:
 * Input:
 * ```php
 * [
 *   'first_name' => 'John',
 *   'last_name' => 'Doe',
 *   'contact_info' => [
 *       'phone_number' => '123456789',
 *   ],
 * ]
 * ```
 * Output:
 * ```php
 * [
 *   'firstName' => 'John',
 *   'lastName' => 'Doe',
 *   'contactInfo' => [
 *       'phoneNumber' => '123456789',
 *   ],
 * ]
 * ```
 */
trait ConvertsSnakeToCamelOutput
{
    /**
     * Recursively convert all array keys to camelCase.
     *
     * @param array $array Input array with snake_case keys.
     * @return array The array with all keys converted to camelCase.
     */
    protected function camelCaseKeys(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[Str::camel($key)] = is_array($value)
                ? $this->camelCaseKeys($value)
                : $value;
        }
        return $result;
    }

    /**
     * Override the parent resolve method to ensure camelCase keys in output.
     *
     * @param mixed $request The current request instance (optional).
     * @return array The resolved resource data with camelCase keys.
     */
    public function resolve($request = null): array
    {
        $data = parent::resolve($request);
        return $this->camelCaseKeys($data);
    }
}
