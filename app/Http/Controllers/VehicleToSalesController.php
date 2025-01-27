<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleToSalesController extends Controller
{
    public function index()
    {
        return view('dashboard.vehicle_to_sales_dashboard');
    }
}
