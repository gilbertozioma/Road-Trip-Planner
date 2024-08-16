<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DestinationController;


class UserDashboardController extends Controller
{
    /**
     * DestinationController instance for accessing destination-related methods.
     *
     * @var DestinationController
     */
    public $destinationController;

    /**
     * Constructor to initialize the DestinationController instance.
     *
     * @param DestinationController $destinationController
     */
    public function __construct(DestinationController $destinationController)
    {
        $this->destinationController = $destinationController;
    }

    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user(); 
        $destinations = Destination::where('creator_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        $lastDestination = $destinations->first();
        $destinationsForSummary = $destinations;
        $summary = $this->getSummary($destinationsForSummary);
        
        return view('dashboard', compact('lastDestination', 'summary'));
    }

    /**
     * Calculate the summary (total distance and time) for a collection of destinations.
     *
     * @param \Illuminate\Database\Eloquent\Collection $destination
     * @return array
     */
    public function getSummary($destination)
    {
        $calculateSummary = $this->destinationController->calculateSummary($destination);
        
        return $calculateSummary;
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
    public function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $haversineDistance = $this->destinationController->haversineDistance($lat1, $lon1, $lat2, $lon2);

        return $haversineDistance;
    }

}
