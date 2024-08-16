<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinationMapController extends Controller
{
    /**
     * Show the destination listing in LeafletJS map.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('destinations.map');
    }
}
