<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->role = $user->role ?? 'user';
    }

    public function updated(User $user): void
    {
    }

    public function deleted(User $user): void
    {
    }

    public function restored(User $user): void
    {
    }
}
