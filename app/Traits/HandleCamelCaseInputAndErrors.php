<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Trait HandleCamelCaseInputAndErrors
 *
 * Provides automatic handling of camelCase request input and validation errors.
 * 
 * ### Responsibilities:
 * - **Input normalization**: Converts all incoming request keys from camelCase → snake_case 
 *   before validation, ensuring consistency with Laravel's Eloquent/database conventions.
 * - **Error formatting**: Converts validation error keys back from snake_case → camelCase 
 *   before returning them to the client, so API responses remain consistent with frontend expectations.
 *
 * This trait is typically used inside **FormRequest** classes.
 *
 * ### Example:
 * #### Input
 * ```json
 * {
 *   "firstName": "John",
 *   "lastName": "Doe"
 * }
 * ```
 * Becomes (internally):
 * ```php
 * [
 *   'first_name' => 'John',
 *   'last_name' => 'Doe',
 * ]
 * ```
 *
 * #### Validation Error
 * ```json
 * {
 *   "message": "Validation failed",
 *   "errors": {
 *     "firstName": ["The first name field is required."]
 *   }
 * }
 * ```
 */
trait HandleCamelCaseInputAndErrors
{
    /**
     * Prepare input data for validation by converting camelCase keys to snake_case.
     *
     * This ensures compatibility with Eloquent/database column naming conventions.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $converted = [];
        foreach ($this->all() as $key => $value) {
            $converted[Str::snake($key)] = $value;
        }

        $this->replace($converted);
    }

    /**
     * Handle validation failure by returning camelCase error keys in JSON response.
     *
     * @param Validator $validator The validator instance containing validation errors.
     * @throws HttpResponseException Always throws with a JSON response containing errors.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $camelCased = [];
        foreach ($errors as $key => $messages) {
            $camelCased[Str::camel($key)] = $messages;
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $camelCased,
        ], 422));
    }
}
