<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;

class TravelRequestPolicy
{
    public function view(User $user, TravelRequest $request): bool
    {
        return $user->id === $request->user_id || $user->is_admin;
    }

    public function updateStatus(User $user, TravelRequest $request): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool { return true; }
    public function list(User $user): bool { return true; }
}
