<?php

namespace Tests\Traits;

use App\Models\User;

trait EnsuresVerifiedUser
{
    protected function verifyUser(User $user): User
    {
        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return $user->fresh();
    }
}
