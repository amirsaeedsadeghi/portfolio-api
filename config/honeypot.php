<?php

return [
    'enabled' => env('HONEYPOT_ENABLED', true),
    'min_delay_seconds' => env('HONEYPOT_MIN_DELAY', 4),
    'honeypot_field' => 'contact_number',
    'token_field' => 'hp_token',
    'grace_seconds' => 3600,
    'reject_message' => 'Invalid submission detected'
];
