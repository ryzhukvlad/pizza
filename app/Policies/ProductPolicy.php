<?php

namespace App\Policies;

use App\Enum\UserRole;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function index(User $user): bool
    {
        return true;
    }

    public function store(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function show(User $user, Product $product): bool
    {
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function destroy(User $user, Product $product): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
