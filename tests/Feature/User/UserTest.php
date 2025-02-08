<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected array $registerUserData;
    protected array $loginUserData;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registerUserData = User::factory()->make()->getAttributes();
        $this->loginUserData = ['email' => 'test@example.com', 'password' => 'password'];
        $this->user = User::factory()->create($this->loginUserData);
    }

    public function test_user_register_success(): void
    {
        $this->post(route('user.register'), $this->registerUserData)->assertOk();
    }

    public function test_user_register_fail(): void
    {
        unset($this->registerUserData['email']);
        $this->post(route('user.register'), $this->registerUserData)->assertUnprocessable();
    }

    public function test_user_login_success(): void
    {
        $this->post(route('user.login'), $this->loginUserData)->assertOk();
    }

    public function test_user_login_fail(): void
    {
        unset($this->loginUserData['password']);
        $this->post(route('user.login'), $this->loginUserData)->assertUnprocessable();
    }

    public function test_user_logout_success(): void
    {
        $newAccessToken = $this->user->createToken($this->user['email']);
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
        $this->get(route('user.logout'), ['Accept' => 'application/json'])->assertUnauthorized();
    }

    public function test_user_profile_success()
    {
        $this->actingAs($this->user)->get(route('user.profile'))->assertOk()->assertSee(html_entity_decode(
            $this->user->name
        ));
    }

    public function test_user_profile_fail(): void
    {
        $this->get(route('user.profile'), ['Accept' => 'application/json'])->assertUnauthorized();
    }
}
