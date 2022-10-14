<?php

namespace Tests\Unit;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_unauthenticated_users_cannot_enter_api_routes() {
        $response = $this->get('/api/solicitud');

        $response->assertStatus(500);
    }

    public function test_authenticated_user_can_enter_api_routes() {
        Sanctum::actingAs(
            User::factory()->make(),
            ["*"]
        );

        $response = $this->get('/api/solicitud');
        $response->assertOk();
    }
}
