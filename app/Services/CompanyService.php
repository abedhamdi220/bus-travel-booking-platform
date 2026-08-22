<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Seats;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function createTripWithSeats(Company $company, array $data, int $driverId, int $vehicleId): Trip
    {
        $driver = Driver::where('company_id', $company->id)->findOrFail($driverId);
        $vehicle = Vehicle::where('company_id', $company->id)->findOrFail($vehicleId);

        abort_unless($driver->state === 'Approved' && $driver->availability_state === 'availability', 422, 'The selected driver is unavailable.');
        abort_unless($vehicle->state === 'availability', 422, 'The selected vehicle is unavailable.');

        $totalSeats = $vehicle->type_vehicle === 'VIP' ? 35 : 45;

        return DB::transaction(function () use ($company, $data, $driver, $vehicle, $totalSeats): Trip {
            $trip = Trip::create([
                'company_id' => $company->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'departure_city' => $data['departure_city'],
                'destination' => $data['destination'],
                'dateTrip' => $data['dateTrip'],
                'timeTrip' => $data['timeTrip'],
                'cost' => $data['cost'],
                'totalSeats' => $totalSeats,
                'trip_type' => $data['trip_type'],
                'recurrence' => $data['recurrence'],
                'days' => $data['days'] ?? null,
                'break_duration' => $data['break_duration'] ?? null,
                'state' => 'scheduled',
            ]);

            $seats = [];
            for ($seatNumber = 1; $seatNumber <= $totalSeats; $seatNumber++) {
                $seats[] = [
                    'trip_id' => $trip->id,
                    'number_seat' => $seatNumber,
                    'state' => 'avaliable',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Seats::insert($seats);

            return $trip;
        });
    }

    public function getTopRouteThisMonth(Company $company): array
    {
        $topRoute = $company->trips()
            ->select('departure_city', 'destination', DB::raw('count(*) as trip_count'))
            ->whereMonth('dateTrip', now()->month)
            ->groupBy('departure_city', 'destination')
            ->orderByDesc('trip_count')
            ->first();

        if (! $topRoute) {
            return ['message' => 'no trip this month'];
        }

        return [
            'message' => 'success',
            'departure_city' => $topRoute->departure_city,
            'destination' => $topRoute->destination,
            'trip_count' => $topRoute->trip_count,
        ];
    }

    public function getCompanyMetrics(Company $company): array
    {
        $totalSeats = (int) $company->trips()->sum('totalSeats');
        $bookedSeats = (int) DB::table('seats')
            ->join('trips', 'seats.trip_id', '=', 'trips.id')
            ->where('trips.company_id', $company->id)
            ->where('seats.state', 'booked')
            ->count();

        return [
            'activtripsThisday' => $company->trips()->whereDate('dateTrip', today())->where('state', 'in_progress')->count(),
            'occupancyRate' => $totalSeats > 0 ? $bookedSeats / $totalSeats : 0,
            'countSeatsAvaliable' => max($totalSeats - $bookedSeats, 0),
            'countSeatsBooked' => $bookedSeats,
            'countVehicle' => $company->vehicles()->count(),
            'countDriver' => $company->drivers()->count(),
        ];
    }

    public function getCompanyProfile(Company $company): array
    {
        return [
            'name' => $company->name,
            'email' => $company->email,
            'phone' => $company->phone,
            'address' => $company->address,
            'bio' => optional($company->profile)->bio,
        ];
    }
}
