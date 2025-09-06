<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HoneypotAndContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_honeypot_token_endpoint_is_private_cache_and_no_store(): void
    {
        $res = $this->get('/api/v1/security/honeypot-token')
            ->assertOk();

        $headers = array_change_key_case($res->headers->all(), CASE_LOWER);

        $this->assertStringContainsString('no-store', $headers['cache-control'][0] ?? '');
        $this->assertStringContainsString('private', $headers['cache-control'][0] ?? '');
    }

    public function test_contact_me_is_rate_limited(): void
    {
        config(['honeypot.enabled' => false]);

        $tokenRes = $this->getJson('/api/v1/security/honeypot-token')->assertOk();
        $data          = $tokenRes->json('data');
        $token         = $data['token'];
        $tokenField    = $data['tokenField'];
        $honeypotField = $data['honeypotField'];


        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/contact-me', [
                'name' => 'XY',
                'email' => 'x@example.com',
                'messageBody' => 'hello',
                $tokenField => $token,
            ])->assertStatus(in_array($i, [0, 1, 2, 3, 4]) ? 201 : 429);
        }
        // next one should be throttled (best-effort; framework timing-dependent)
        $this->postJson('/api/v1/contact-me', [
            'name' => 'XY',
            'email' => 'x@example.com',
            'messageBody' => 'hello again',
            $tokenField => $token,
        ])->assertStatus(429);
    }
}
