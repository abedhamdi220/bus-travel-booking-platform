<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

class ProfilPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function update(User $user, Profile $profile)
    {
        return $user->id === $profile->user_id;
    }
    public function view(User $user, Profile $profile)
    {
        return $user->id === $profile->user_id;
    }
}
