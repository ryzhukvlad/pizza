<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected User $defaultUser;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUser = User::factory()->create();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
    }
}
