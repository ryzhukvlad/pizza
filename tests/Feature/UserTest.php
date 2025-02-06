<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_register_success(): void
    {
        $userData = User::factory()->make()->getAttributes();
        $this->post(route('user.register'), $userData)->assertOk();
    }

    public function test_user_register_fail(): void
    {
        $userData = User::factory()->make()->getAttributes();
        unset($userData['email']);
        $this->post(route('user.register'), $userData)->assertFound();
    }

    public function test_user_login_success(): void
    {
        $userData = ['email' => 'test@example.com', 'password' => 'password'];
        $user = User::factory()->create($userData);
        $this->post(route('user.login'), $userData)->assertOk();
    }

    public function test_user_login_fail(): void
    {
        $userData = ['email' => 'test@example.com', 'password' => 'password'];
        $user = User::factory()->create($userData);
        unset($userData['password']);
        $this->post(route('user.login'), $userData)->assertFound();
    }

    public function test_user_logout_success(): void
    {
        $user = User::factory()->create();

        $newAccessToken = $user->createToken($user['email']);
        $plainTextToken = $newAccessToken->plainTextToken;
        $tokenId = $newAccessToken->accessToken->id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $plainTextToken)
            ->get(route('user.logout'));

        $response->assertOk()
            ->assertJson([
                'Token deleted successfully.',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId
        ]);
    }

    public function test_user_logout_fail(): void
    {
        $this->get(route('user.logout'))->assertNotFound();
    }
}
