<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Seats;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function logout(Request $request)
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function indexDashboard()
    {
        $company = Auth::guard('company')->user();

        $theTOpRoute = $this->companyService->getTopRouteThisMonth($company);
        $information = $this->companyService->getCompanyMetrics($company);
        $recentBuses = $company->vehicles()->latest()->take(5)->get();
        $upcomingTrips = $company->trips()
            ->whereDate('dateTrip', '>=', today())
            ->latest('dateTrip')
            ->take(5)
            ->get();
        $recentBookings = DB::table('bookings')
            ->join('trips', 'bookings.trip_id', '=', 'trips.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('trips.company_id', $company->id)
            ->select('bookings.id as booking_id', 'users.name as passenger_name', 'trips.departure_city', 'trips.destination', 'trips.cost')
            ->orderByDesc('bookings.dateBooking')
            ->take(5)
            ->get();

        return view('company.dashboard', compact('theTOpRoute', 'information', 'recentBuses', 'upcomingTrips', 'recentBookings'));
    }

    public function indexProfile()
    {
        return view('company.profile', [
            'information' => $this->companyService->getCompanyProfile(Auth::guard('company')->user()),
        ]);
    }

    public function showTrips()
    {
        $trips = Trip::with('seats')
            ->where('company_id', Auth::guard('company')->id())
            ->latest('dateTrip')
            ->get();

        return view('company.trips', compact('trips'));
    }

    public function createTrip()
    {
        $companyId = Auth::guard('company')->id();
        $drivers = Driver::with('user')
            ->where('company_id', $companyId)
            ->where('state', 'Approved')
            ->where('availability_state', 'availability')
            ->get();
        $vehicles = Vehicle::where('company_id', $companyId)
            ->where('state', 'availability')
            ->get();

        return view('company.addTrip', compact('drivers', 'vehicles'));
    }

    /**
     * Create a trip submitted from the company web dashboard.
     */
    public function storeTrip(Request $request)
    {
        $data = $request->validate([
            'departure_city' => ['required', 'string', 'max:50'],
            'destination' => ['required', 'string', 'max:50'],
            'dateTrip' => ['required', 'date'],
            'timeTrip' => ['required', 'date_format:H:i'],
            'cost' => ['required', 'integer', 'min:0'],
            'tripType' => ['required', 'in:VIP,nurmal'],
            'driver_id' => ['required', 'integer'],
            'vehicle_id' => ['required', 'integer'],
        ]);

        $trip = $this->companyService->createTripWithSeats(
            company: Auth::guard('company')->user(),
            data: [
                ...$data,
                'trip_type' => 'one_way',
                'recurrence' => 'one_time',
                'days' => null,
                'break_duration' => null,
            ],
            driverId: (int) $data['driver_id'],
            vehicleId: (int) $data['vehicle_id'],
        );

        return redirect()->route('company.trips')
            ->with('success', "Trip #{$trip->id} scheduled successfully with {$trip->totalSeats} seats.");
    }

    /**
     * Create a recurring or one-time trip through the API contract.
     */
    public function addTrip(TripRequest $request, int $driver_id, int $vehicle_id)
    {
        $trip = $this->companyService->createTripWithSeats(
            company: Auth::guard('company')->user(),
            data: $request->validated(),
            driverId: $driver_id,
            vehicleId: $vehicle_id,
        );

        return response()->json([
            'message' => 'Trip created successfully.',
            'trip' => $trip->load('seats'),
        ], 201);
    }

    public function showMyTrip()
    {
        $trips = Trip::with('seats')
            ->where('company_id', Auth::guard('company')->id())
            ->latest('dateTrip')
            ->get();

        return response()->json([
            'message' => 'Company trips retrieved successfully.',
            'trips' => $trips,
        ]);
    }

    public function updateTrip(Request $request, int $id)
    {
        $data = $request->validate([
            'cost' => ['sometimes', 'integer', 'min:0'],
            'dateTrip' => ['sometimes', 'date'],
            'timeTrip' => ['sometimes', 'date_format:H:i'],
            'departure_city' => ['sometimes', 'string', 'max:50'],
            'destination' => ['sometimes', 'string', 'max:50'],
            'trip_type' => ['sometimes', 'in:one_way,round_trip'],
            'recurrence' => ['sometimes', 'in:daily,weekly,one_time'],
            'days' => ['nullable', 'array'],
            'days.*' => ['in:sun,mon,tue,wed,thu,fri,sat'],
            'break_duration' => ['nullable', 'date_format:H:i'],
        ]);

        $trip = Trip::where('company_id', Auth::guard('company')->id())->findOrFail($id);
        $trip->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Trip updated successfully.',
                'trip' => $trip->fresh(),
            ]);
        }

        return back()->with('success', 'Trip updated successfully.');
    }

    public function deleteTrip(Request $request, int $id)
    {
        $trip = Trip::where('company_id', Auth::guard('company')->id())->findOrFail($id);

        DB::transaction(function () use ($trip): void {
            $trip->seats()->delete();
            $trip->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Trip deleted successfully.']);
        }

        return back()->with('success', 'Trip deleted successfully.');
    }

    public function showMenuPassengerOnTrip(int $tripId)
    {
        $trip = Trip::with('seats')
            ->where('company_id', Auth::guard('company')->id())
            ->findOrFail($tripId);

        return response()->json([
            'message' => 'Trip seats retrieved successfully.',
            'trip' => $trip,
        ]);
    }

    public function searchInCompanies(string $name)
    {
        $companies = Company::query()
            ->where('state', 'Approved')
            ->where('name', 'like', "%{$name}%")
            ->orderBy('name')
            ->get(['id', 'name', 'address', 'phone']);

        return response()->json(['companies' => $companies]);
    }

    public function updateProfile(Request $request)
    {
        $company = Auth::guard('company')->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:companies,email,'.$company->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $company->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
        ]);
        $company->profile()->updateOrCreate(
            ['company_id' => $company->id],
            ['bio' => $data['bio'] ?? null],
        );

        return back()->with('success', 'Company profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $company = Auth::guard('company')->user();

        if (! Hash::check($data['current_password'], $company->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $company->update(['password' => Hash::make($data['new_password'])]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateLogo(Request $request)
    {
        $request->validate(['logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048']]);
        $company = Auth::guard('company')->user();

        $path = $request->file('logo')->store('documents', 'public');
        $company->documents()->updateOrCreate(
            ['typeFile' => 'logoCompany'],
            ['pathDoc' => $path],
        );

        return back()->with('success', 'Company logo updated successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies'],
            'phone' => ['required', 'string', 'max:20', 'unique:companies'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::create([
            ...$validator->validated(),
            'password' => Hash::make($request->password),
            'state' => 'Pending',
        ]);

        return response()->json([
            'message' => 'Company registered successfully.',
            'company' => $company,
            'token' => $company->createToken('company-token')->plainTextToken,
        ], 201);
    }

    public function fetchNotifications()
    {
        $company = Auth::guard('company')->user();

        if (! $company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $notifications = $company->notifications()->latest()->take(10)->get();
        $formattedNotifications = $notifications->map(function ($notification): array {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'You have a new notification.',
                'time' => $notification->created_at->diffForHumans(),
                'read' => $notification->read_at !== null,
            ];
        });

        return response()->json([
            'unread_count' => $company->unreadNotifications()->count(),
            'notifications' => $formattedNotifications,
        ]);
    }

    public function markNotificationsAsRead()
    {
        $company = Auth::guard('company')->user();

        if (! $company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $company->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
