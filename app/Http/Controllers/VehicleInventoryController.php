<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class VehicleInventoryController extends Controller
{
    public function index() {

        if(Auth::check()){
            return view('vehicle_inventory.vehicle_inventory');
        }else{
            return view('index');
        }
    }

    public function inventoryList(Request $request){

         // dd($request->start_date);
         $query = Inventory::with(['vehicle'])
                        //  ->whereNull('deleted_at')
                        ;
 
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
             return $data->vehicle->unit;
         })

         ->editColumn('color', function($data) {
             return $data->vehicle->color;
         })
 
         ->editColumn('cs_number', function($data) {
             return $data->CS_number;
         })
 
         ->editColumn('model', function($data) {
             return $data->vehicle->variant;
         })

         ->addColumn('tags', function($data) {
            return '';
        })

         ->addColumn('invoice_number', function($data) {
            return $data->invoice_number;
        })
 
        
 
         ->make(true);

    }

    public function getTotalInventory(){
        
        $query = Inventory::with(['vehicle']);
        $totalInventory = $query->count();

        return response()->json(['totalInventory' => $totalInventory]);
    }

}
