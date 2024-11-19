<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleReleasesController extends Controller
{
    public function index() {
        return view('vehicle_releases.vehicle_releases'); // Ensure this view file exists in your resources/views directory
    }
}
