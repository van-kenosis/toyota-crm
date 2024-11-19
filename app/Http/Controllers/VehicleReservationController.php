<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleReservationController extends Controller
{
    public function index() {
        return view('vehicle_reservation.vehicle_reservation');
    }
}
