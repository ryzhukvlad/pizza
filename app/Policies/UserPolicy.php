<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function login(User $user): bool
    {
        return !auth('sanctum')->check();
    }

    public function logout(User $user): bool
    {
        return auth('sanctum')->check();
    }

    public function current(User $user): bool
    {
        return auth('sanctum')->check();
    }

    public function cart(User $user): bool
    {
        return auth('sanctum')->check();
    }
}
