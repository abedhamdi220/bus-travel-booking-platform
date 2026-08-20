<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Trip;
use App\Models\User;

class TripPolicy
{

    public function __construct()
    {
        //
    }

    public function showMenuPassenger(Company $company, Trip $trip)
    {
        return $company->id === $trip->company_id;
    }
}
