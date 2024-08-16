<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DestinationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the destination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // $this->authorize('manage_destination');

        $destinationQuery = Destination::query();
        $destinationQuery->orderBy('created_at', 'desc');
        $destinationQuery->where('name', 'like', '%' . request('q') . '%');
        $destinationQuery->where('creator_id', Auth::id());

        // Paginate the destinations for display
        $destinations = $destinationQuery->paginate(25);

        return view('destinations.index', compact('destinations'));
    }


    /**
     * Show the form for creating a new destination.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', new Destination);

        return view('destinations.create');
    }

    /**
     * Store a newly created destination in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Destination);

        $newDestination = $request->validate([
            'name'      => 'required|max:60',
            'address'   => 'nullable|max:255',
            'latitude'  => 'nullable|required_with:longitude|max:15',
            'longitude' => 'nullable|required_with:latitude|max:15',
        ]);

        $newDestination['creator_id'] = Auth::id();

        $destination = Destination::create($newDestination);

        return redirect()->route('destinations.show', $destination);
    }

    /**
     * Display the specified destination.
     *
     * @param  \App\Destination $destination
     * @return \Illuminate\View\View
     */
    public function show(Destination $destination)
    {
        // Get the destinations for summary calculation
        $destinationsForSummary = $destination->all();
        $summary = $this->calculateSummary($destinationsForSummary);
        return view('destinations.show', compact('destination', 'summary'));
    }
    
    /**
     * Calculate the distance between two points using the Haversine formula.
     * 
     * Because I did not use API, I hardcoded the average speed of 80 km/h
     *
     * @param  \Illuminate\Database\Eloquent\Collection $destination
     * @return array
     * @throws \Exception
     */
    public function calculateSummary($destination)
    {
        $totalDistance = 0;
        $totalTime = 0;

        //  Destinations to calculate distances
        if ($destination->count() < 2) {
            return [
                'totalDistance' => 0,
                'totalTime' => 0,
            ];
        }

        for ($i = 0; $i < $destination->count() - 1; $i++) {
            $startLat = $destination[$i]->latitude;
            $startLng = $destination[$i]->longitude;
            $endLat = $destination[$i + 1]->latitude;
            $endLng = $destination[$i + 1]->longitude;

            // Calculate distance using the Haversine formula
            $distance = $this->haversineDistance($startLat, $startLng, $endLat, $endLng);
            $totalDistance += $distance;
            $totalTime += $distance / 80; // Assuming an average speed of 80 km/h
        }

        return [
            'totalDistance' => round($totalDistance, 2),
            'totalTime' => round($totalTime * 60, 2)
        ];
    }

    /**
     * Calculate the distance between two points using the Haversine formula.
     *
     * @param float $lat1 Latitude of the first point
     * @param float $lon1 Longitude of the first point
     * @param float $lat2 Latitude of the second point
     * @param float $lon2 Longitude of the second point
     * @return float Distance in kilometers
     */
    public function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        // Convert latitude and longitude to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Calculate the differences between the latitudes and longitudes
        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        // Apply the Haversine formula
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($lat1) * cos($lat2) *
            sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calculate the distance
        $distance = $earthRadius * $c;

        return $distance;
    }


    /**
     * Show the form for editing the specified destination.
     *
     * @param  \App\Destination $destination
     * @return \Illuminate\View\View
     */
    public function edit(Destination $destination)
    {
        $this->authorize('update', $destination);

        return view('destinations.edit', compact('destination'));
    }

    /**
     * Update the specified destination in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination $destination
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Destination $destination)
    {
        $this->authorize('update', $destination);

        $destinationData = $request->validate([
            'name'      => 'required|max:60',
            'address'   => 'nullable|max:255',
            'latitude'  => 'nullable|required_with:longitude|max:15',
            'longitude' => 'nullable|required_with:latitude|max:15',
        ]);
        $destination->update($destinationData);

        return redirect()->route('destinations.show', $destination);
    }

    /**
     * Remove the specified destination from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination $destination
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Destination $destination)
    {
        $this->authorize('delete', $destination);

        $request->validate(['destination_id' => 'required']);

        if ($request->get('destination_id') == $destination->id && $destination->delete()) {
            return redirect()->route('destinations.index');
        }

        return back();
    }
}
