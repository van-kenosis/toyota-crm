<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VehicleReleasesController extends Controller
{
    public function index() {
        return view('vehicle_releases.vehicle_releases'); // Ensure this view file exists in your resources/views directory
    }

   
}
