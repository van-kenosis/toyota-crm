<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Status;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VehicleToSalesController extends Controller
{
    public function index()
    {
        return view('dashboard.vehicle_to_sales_dashboard');
    }

    public function totalDeliveriesToday(Request $request){

        $query = Inventory::whereNull('deleted_at');

            if ($request->has('date_range') && !empty($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            }else {
                $startDate = Carbon::today();
                $endDate = Carbon::today();
            }
            $query->whereBetween('delivery_date', [$startDate, $endDate]);

        $deliveryCount = $query->count();

        return response()->json(['deliveryCount' => $deliveryCount]);

    }

    public function getReleasedToday(Request $request){
        $released_status = Status::where('status', 'like', 'Released')->first();
        $posted_status = Status::where('status', 'like', 'Posted')->first();

            $query = Transactions::with(['inquiry', 'inventory', 'application'])
            ->whereNull('deleted_at')
            ->whereNotNull('reservation_id')
            ->whereIn('reservation_transaction_status', [$released_status->id, $posted_status->id]);

            if ($request->has('date_range') && !empty($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            }else {
                $startDate = Carbon::today();
                $endDate = Carbon::today();
            }
            $query->whereBetween('updated_at', [$startDate, $endDate]);



        $releasedCount = $query->count();

        return response()->json(['releasedCount' => $releasedCount]);
    }


}
