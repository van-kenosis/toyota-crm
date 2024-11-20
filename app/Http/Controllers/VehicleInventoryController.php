<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleInventoryController extends Controller
{
    public function index() {
        return view('vehicle_inventory.vehicle_inventory'); // Ensure this view file exists in your resources/views directory
    }
}
