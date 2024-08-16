<?php

namespace App\Http\Controllers\Api;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Destination as DestinationResource;

class DestinationController extends Controller
{
    /**
     * Get destination listing on Leaflet JS geoJSON data structure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $destinations = Destination::all();

        $geoJSONdata = $destinations->map(function ($destination) {
            return [
                'type'       => 'Feature',
                'properties' => new DestinationResource($destination),
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [
                        $destination->longitude,
                        $destination->latitude,
                    ],
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $geoJSONdata,
        ]);
    }
}
