<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\HandleHoneypot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Honeypot
 *
 * Retrieve honeypot configuration and a one-time token for form protection.
 *
 * This endpoint provides a server-generated token and field names used to detect bots.
 * Use it before rendering or submitting protected forms.
 */
class HoneypotController extends Controller
{
    use ApiResponse, HandleHoneypot;

    /**
     * Get honeypot token and config
     *
     * Returns a token and field configuration required to enable honeypot checks on the client side.
     *
     * @unauthenticated
     *
     * @response 200 {
     *   "status": true,
     *   "message": "OK",
     *   "data": {
     *     "token": "eyJpdiI6Ilc0RmtiTnZyYkc0Y1p5NHZVWFVVVFE9PSIsInZhbHVlIjoiUENXVFJFK1R1OE1lc2lhbFpBeDd3UT09IiwibWFjIjoiMzNjOGI5NTQxOTE2YTlmM2Q4NTQyYzc2ZmI0MWY1NGM1Y2Y1NzUyMmFhZGRlZjBmYzQ5ZTZmYzlmMjIyNzg0YyIsInRhZyI6IiJ9",
     *     "tokenField": "hp_token",
     *     "honeypotField": "contact_number",
     *     "minDelay": 0,
     *     "graceSeconds": 3600
     *   }
     * }
     */
    public function __invoke(): JsonResponse
    {
        $token = static::makeHoneypotToken();

        return $this->success([
            'token'         => $token,
            'tokenField'    => config('honeypot.token_field'),
            'honeypotField' => config('honeypot.honeypot_field'),
            'minDelay'      => (int) config('honeypot.honeypot.min_delay_seconds'),
            'graceSeconds'  => (int) config('honeypot.grace_seconds'),
        ]);
    }
}
