<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user(); 
        $destinations = Destination::where('creator_id', $user->id)->orderBy('created_at', 'desc')->get();
        $hasMultipleDestinations = $destinations->count() > 1;
        $summary = $hasMultipleDestinations ? $this->calculateSummary($destinations) : null;

        // Get the last destination
        $lastDestination = $destinations->first();

        return view('dashboard', compact('hasMultipleDestinations', 'summary', 'lastDestination'));
    }

    /**
     * Calculate the summary (total distance and time) for a collection of destinations.
     *
     * @param \Illuminate\Database\Eloquent\Collection $destinations
     * @return array
     */
    private function calculateSummary($destinations)
    {
        $calculateSummary = (new DestinationController)->calculateSummary($destinations);

        return $calculateSummary;
    }
}
