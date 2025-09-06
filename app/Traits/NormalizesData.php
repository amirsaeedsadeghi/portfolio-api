<?php

namespace App\Traits;

/**
 * Trait NormalizesData
 *
 * Provides utility methods for **sanitizing and normalizing input data**
 * before persisting to the database or updating models.
 *
 * This trait is commonly used in repositories to:
 * - Restrict incoming data to allowed fields.
 * - Merge missing fields with default values.
 * - Enforce type casting for consistency and integrity.
 * - Safely serialize arrays to JSON strings.
 *
 * ### Responsibilities
 * - **`onlyFields()`**: Prevents mass assignment by whitelisting only the expected keys.
 * - **`mergeDefaults()`**: Ensures required defaults (e.g. `order = 1`) are always present.
 * - **`forceCast()`**: Normalizes data types (`int`, `string`, `date`, etc.) or applies custom closures.
 * - **`toJson()`**: Converts arrays into JSON with UTF-8 and slashes preserved.
 *
 * ### Example
 * ```php
 * $data = [
 *   'title' => 'My Project',
 *   'order' => '5',
 *   'tags'  => ['php', 'laravel'],
 * ];
 *
 * $normalized = $this->forceCast(
 *     $this->mergeDefaults(
 *         $this->onlyFields($data, ['title', 'order', 'tags']),
 *         ['order' => 1]
 *     ),
 *     ['order' => 'int', 'tags' => 'json']
 * );
 *
 * // Result:
 * [
 *   'title' => 'My Project',
 *   'order' => 5,
 *   'tags'  => "[\"php\",\"laravel\"]"
 * ]
 * ```
 */
trait NormalizesData
{
    /**
     * Keep only whitelisted fields from input data.
     *
     * @param array $data   The raw input data.
     * @param array $fields Allowed keys.
     * @return array Filtered array containing only allowed fields.
     */
    protected function onlyFields(array $data, array $fields): array
    {
        return array_intersect_key($data, array_flip($fields));
    }

    /**
     * Merge defaults into data, overriding missing keys.
     *
     * @param array $data     The input data.
     * @param array $defaults Default key-value pairs.
     * @return array Merged array with defaults applied.
     */
    protected function mergeDefaults(array $data, array $defaults): array
    {
        return array_merge($defaults, $data);
    }

    /**
     * Force type casting or transformation for specific keys.
     *
     * Supported rules: `int`, `bool`, `json`, `string`, `date`, `datetime`
     * or a custom closure.
     *
     * @param array $data  The input data.
     * @param array $casts Key-to-type map.
     * @return array Normalized data with type-casted values.
     */
    protected function forceCast(array $data, array $casts): array
    {
        foreach ($casts as $key => $rule) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];

            $data[$key] = match (true) {
                is_callable($rule) => $rule($value),
                $rule === 'int'    => (int) $value,
                $rule === 'bool'   => (bool) $value,
                $rule === 'json'   => $this->toJson($value),
                $rule === 'string' => (string) $value,
                $rule === 'date'   => date('Y-m-d', strtotime($value)),
                $rule === 'datetime' => date('Y-m-d H:i:s', strtotime($value)),
                default            => $value,
            };
        }

        return $data;
    }

    /**
     * Convert array to JSON string with UTF-8 and slashes preserved.
     *
     * @param array $value Array value to encode.
     * @return string JSON encoded string.
     */
    protected function toJson(array $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
