<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;

trait HandleHoneypot
{
    /**
     * Generate encrypted timestamp token to embed in form.
     */
    public static function makeHoneypotToken(?int $issuedAt = null): string
    {
        $ts = $issuedAt ?? now()->timestamp;
        // Encrypt timestamp
        return Crypt::encryptString((string) $ts);
    }

    /**
     * Validate honeypot on a request. Throws ValidationException on failure.
     */
    public function assertHoneypot(Request $request, array $options = []): void
    {
        if (!config('honeypot.enabled')) {
            return;
        }

        $hpField   = $options['honeypot_field']    ?? config('honeypot.honeypot_field', 'contact_number');
        $tokenField = $options['token_field']       ?? config('honeypot.token_field', 'hp_token');
        $minDelay  = (int)($options['min_delay']   ?? config('honeypot.min_delay_seconds', 4));
        $grace     = (int)($options['grace']       ?? config('honeypot.grace_seconds', 3600));
        $message   = $options['message']           ?? config('honeypot.reject_message', 'Invalid submission detected.');

        // Fail if hpField fill
        if ($request->filled($hpField)) {
            throw ValidationException::withMessages([$hpField => [$message]]);
        }

        // Token must be valid
        $token = $request->input($tokenField);
        if (!$token) {
            throw ValidationException::withMessages([$tokenField => [$message]]);
        }

        try {
            $issuedAt = (int) Crypt::decryptString($token);
        } catch (DecryptException) {
            throw ValidationException::withMessages([$tokenField => [$message]]);
        }

        $now = now()->timestamp;
        $elapsed = $now - $issuedAt;

        // Check minimum time for filling
        if ($elapsed < $minDelay) {
            throw ValidationException::withMessages([$tokenField => [$message]]);
        }

        // Validate for token to prevent old one
        if ($elapsed > $grace) {
            throw ValidationException::withMessages([$tokenField => [$message]]);
        }
    }
}
