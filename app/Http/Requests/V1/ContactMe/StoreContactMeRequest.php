<?php

namespace App\Http\Requests\V1\ContactMe;

use App\Traits\HandleCamelCaseInputAndErrors;
use App\Traits\HandleHoneypot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreContactMeRequest extends FormRequest
{
    use HandleCamelCaseInputAndErrors;

    use HandleHoneypot;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|min:2|max:70',
            'email' => 'required|email',
            'message_body' => 'required|string|min:3|max:2000',
            // Honeypot fields:
            config('honeypot.honeypot_field') => 'nullable|string|max:255',
            config('honeypot.token_field') => 'required|string'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            try {
                $this->assertHoneypot($this);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $msg) {
                        $validator->errors()->add($field, $msg);
                    }
                }
            }
        });
    }
}
