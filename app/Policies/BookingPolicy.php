<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function deletePolice(User $user,Booking $booking)
    {
        return $user->id === $booking->user_id;
    }
}
