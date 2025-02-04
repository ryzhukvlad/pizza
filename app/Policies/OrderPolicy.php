<?php

namespace App\Policies;

use App\Enum\UserRole;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function index(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function store(User $user): bool
    {
        return auth('sanctum')->check();
    }

    public function show(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
