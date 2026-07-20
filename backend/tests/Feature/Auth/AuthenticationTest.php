<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_view_their_own_data(): void
    {
        $response = $this->postJson('/api/register', [
            'organization_name' => 'Corretora Exemplo',
            'name' => 'Ana Corretora',
            'email' => 'ana@exemplo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.name', 'Ana Corretora')
            ->assertJsonPath('user.organization.name', 'Corretora Exemplo');

        $this->assertDatabaseHas('organizations', [
            'name' => 'Corretora Exemplo',
            'timezone' => 'America/Sao_Paulo',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Ana Corretora',
            'email' => 'ana@exemplo.com',
            'is_active' => true,
        ]);

        $this->withToken($response->json('token'))
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('user.email', 'ana@exemplo.com')
            ->assertJsonPath('user.organization.name', 'Corretora Exemplo');
    }

    public function test_user_can_log_in_and_log_out(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->for($organization)->create([
            'email' => 'corretor@exemplo.com',
            'password' => 'password123',
        ]);

        $login = $this->postJson('/api/login', [
            'email' => 'corretor@exemplo.com',
            'password' => 'password123',
        ]);

        $login
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.organization.id', $organization->id);

        $token = $login->json('token');

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertNoContent();

        $this->assertNull(PersonalAccessToken::findToken($token));
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::factory()->create([
            'email' => 'inativo@exemplo.com',
            'password' => 'password123',
            'is_active' => false,
        ]);

        $this->postJson('/api/login', [
            'email' => 'inativo@exemplo.com',
            'password' => 'password123',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }
}
