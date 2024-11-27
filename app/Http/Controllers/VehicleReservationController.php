<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Inventory;
use App\Models\Status;
use App\Models\Team;
use App\Models\Transactions;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VehicleReservationController extends Controller
{
    public function index() {
        return view('vehicle_reservation.vehicle_reservation');
    }

    public function availableUnitsList(){
        DB::statement("SET SQL_MODE=''");
        
        $query = Vehicle::with('inventory')
                        ->whereNull('deleted_at')
                        ->whereHas('inventory', function($subQuery) {
                            $subQuery->where('status', 'available');
                            $subQuery->where('CS_number_status', 'available');
                        })
                        ->groupBy('unit');

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })
        ->addColumn('unit', function($data) {
            return $data->unit;
        })
        ->addColumn('quantity', function($data) {

            $count = Inventory::with('vehicle')
            ->whereHas('vehicle', function($subQuery) use($data) {
                $subQuery->where('unit', $data->unit);
            })
            ->where('status', 'available')
            ->where('CS_number_status', 'available')
            ->count();
          
            return $count;
        })
        ->make(true);
    }

    public function getReservedCount(){
        $pending_status = Status::where('status', 'like', 'pending')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
            ->whereNull('deleted_at')
            ->whereNotNull('reservation_id')
            ->where('reservation_transaction_status', $pending_status->id);
        $count = $query->count();

        return response()->json(['count' => $count]);
    }

    public function list_pending(Request $request){

        // dd($request->start_date);
        $pending_status = Status::where('status', 'like', 'pending')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
                        ->whereNull('deleted_at')
                        ->whereNotNull('reservation_id')
                        ->where('reservation_transaction_status', $pending_status->id);

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        // dd($list->toArray());

        return DataTables::of($list)
        ->editColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->editColumn('unit', function($data) {
            return $data->application->vehicle->unit;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->inquiry->customer->customer_first_name . ' ' . $data->inquiry->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->inquiry->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->inquiry->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->inquiry->customer->department_name; 
            } 
        })
        
        ->editColumn('year_model', function($data) {
            return $data->inventory->year_model;
        })

        ->addColumn('variant', function($data) {
            return $data->application->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->application->vehicle->color;
        })

        ->addColumn('cs_number', function($data) {
            return $data->inventory->CS_number;
        })

        ->addColumn('trans_type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })
        ->addColumn('trans_bank', function($data) {
            return '';
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->application->updatedBy->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->application->updatedBy->first_name . ' ' . $data->application->updatedBy->last_name;
        })

        ->addColumn('date_assigned', function($data) {
            return $data->reservation_date;
        })

        ->make(true);
    }

    public function list_released(Request $request){

        // dd($request->start_date);
        $released_status = Status::where('status', 'like', 'released')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
                        ->whereNull('deleted_at')
                        ->whereNotNull('reservation_id')
                        ->where('reservation_transaction_status', $released_status->id);

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        // dd($list->toArray());

        return DataTables::of($list)
        ->editColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->editColumn('unit', function($data) {
            return $data->application->vehicle->unit;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->inquiry->customer->customer_first_name . ' ' . $data->inquiry->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->inquiry->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->inquiry->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->inquiry->customer->department_name; 
            } 
        })
        
        ->editColumn('year_model', function($data) {
            return $data->inventory->year_model;
        })

        ->addColumn('variant', function($data) {
            return $data->application->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->application->vehicle->color;
        })

        ->addColumn('cs_number', function($data) {
            return $data->inventory->CS_number;
        })

        ->addColumn('trans_type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })
        ->addColumn('trans_bank', function($data) {
            return '';
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->application->updatedBy->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->application->updatedBy->first_name . ' ' . $data->application->updatedBy->last_name;
        })

        ->addColumn('date_assigned', function($data) {
            return $data->reservation_date;
        })

        ->make(true);
    }

    
    public function reservationPerTeam(){
        DB::statement("SET SQL_MODE=''");
        
        $query = Team::whereNull('deleted_at');
                        

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })
        ->addColumn('team', function($data) {
            return $data->name;
        })

        ->addColumn('quantity', function($data) {
            $pending_status = Status::where('status', 'like', 'pending')->first();
            $count = Transactions::with(['inquiry', 'inventory', 'application'])
            ->whereNull('deleted_at')
            ->where('team_id', $data->id)
            ->whereNotNull('reservation_id')
            ->where('reservation_transaction_status', $pending_status->id)
            ->count();
           
            return $count;
        })
        
        ->make(true);
    }





}
