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
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use Yajra\DataTables\Facades\DataTables;

class VehicleReservationController extends Controller
{
    public function index() {
        if(Auth::check()){
            return view('vehicle_reservation.vehicle_reservation');
        }else{
            return view('index');
        }
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
        $reserved_status = Status::where('status', 'like', 'Reserved')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
            ->whereNull('deleted_at')
            ->whereNotNull('inventory_id')
            ->whereNotNull('reservation_id')
            ->where('reservation_transaction_status', $reserved_status->id);
        $count = $query->count();

        return response()->json(['count' => $count]);
    }

    public function list_pending(Request $request){

        // dd($request->start_date);
        DB::statement("SET SQL_MODE=''");
        $pending_status = Status::where('status', 'like', 'pending')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
                        ->where('reservation_transaction_status', $pending_status->id)
                        ->whereNull('deleted_at')
                        ->whereNotNull('reservation_id')
                        ;

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->groupBy('application_id')->get();

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
            return '';
        })

        ->addColumn('variant', function($data) {
            return $data->application->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->application->vehicle->color;
        })

        ->addColumn('cs_number', function($data) {
            return '';
        })

        ->addColumn('trans_type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })
        ->addColumn('trans_bank', function($data) {
            return $data->application->bank->bank_name ?? '';
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

    public function list_reserved(Request $request){

        // dd($request->start_date);
        $reserved_status = Status::where('status', 'like', 'Reserved')->first();
        $query = Transactions::with(['inquiry', 'inventory', 'application'])
                        ->whereNull('deleted_at')
                        ->where('reservation_transaction_status', $reserved_status->id)
                        ->whereNotNull('reservation_id')
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
            return $data->inventory->year_model ?? '';
        })

        ->addColumn('variant', function($data) {
            return $data->application->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->application->vehicle->color;
        })

        ->addColumn('cs_number', function($data) {
            return $data->inventory->CS_number ?? 'Select cs number';
        })

        ->addColumn('trans_type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })
        ->addColumn('trans_bank', function($data) {
            return $data->application->bank->bank_name ?? '';
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
        ->addColumn('vehicle_id', function($data) {
            return $data->application->vehicle_id ?? '';
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
            $reserved_status = Status::where('status', 'like', 'Reserved')->first();
            $count = Transactions::with(['inquiry', 'inventory', 'application'])
            ->whereNotNull('inventory_id')
            ->whereNull('deleted_at')
            ->where('team_id', $data->id)
            ->whereNotNull('reservation_id')
            ->where('reservation_transaction_status', $reserved_status->id)
            ->count();

            return $count;
        })

        ->make(true);
    }

    public function processing_pending(Request $request){
        try {

            $approved_status = Status::where('status', 'like', 'approved')->first()->id;
            $pending_status = Status::where('status', 'like', 'pending')->first()->id;
            $cancel_status = Status::where('status', 'like', 'cancel')->first()->id;
            $processing_status = Status::where('status', 'like', 'Processed')->first()->id;
            $reserved_status = Status::where('status', 'like', 'Reserved')->first()->id;
            $pending_for_release_status = Status::where('status', 'like', 'Pending For Release')->first()->id;

            $transaction_pendings = Transactions::where('application_id', $request->id)
            ->where('reservation_transaction_status', $pending_status)
            ->whereNull('deleted_at')
            ->get();

            foreach ($transaction_pendings as $transaction) {
                $transaction->status = $reserved_status;
                $transaction->reservation_transaction_status = $reserved_status;
                $transaction->reservation_date = now();
                $transaction->save();

            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle reservation request successfully processed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating reservation process: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processing_reserved(Request $request){
        try {

            $approved_status = Status::where('status', 'like', 'approved')->first()->id;
            $pending_status = Status::where('status', 'like', 'pending')->first()->id;
            $cancel_status = Status::where('status', 'like', 'cancel')->first()->id;
            $processing_status = Status::where('status', 'like', 'Processed')->first()->id;
            $reserved_status = Status::where('status', 'like', 'Reserved')->first()->id;
            $pending_for_release_status = Status::where('status', 'like', 'Pending For Release')->first()->id;

            $transaction_pendings = Transactions::where('id', decrypt($request->id))
            ->where('reservation_transaction_status', $reserved_status)
            ->whereNull('deleted_at')
            ->get();

            foreach ($transaction_pendings as $transaction) {
                $transaction->status = $pending_for_release_status;
                $transaction->reservation_transaction_status = $pending_for_release_status;
                $transaction->reservation_date = now();
                $transaction->save();

            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle reservation successfully processed to pending for release'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating reservation process: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCSNumberByVehicleId($vehicle_id) {
        $inventories = Inventory::with('transaction')
            ->where('vehicle_id', $vehicle_id)
            ->where('status', 'available')
            ->where('CS_number_status', 'available')->get()->toArray();
        return response()->json($inventories);
    }

    public function addCSNumber(Request $request){

        // dd($request->all());
        try {
            $transaction = Transactions::FindOrFail(decrypt($request->transaction_id));
            if ($transaction->inventory_id) {
                $inventory = Inventory::find($transaction->inventory_id);
                if ($inventory) {
                    $inventory->CS_number_status = 'available';
                    $inventory->status = 'available';
                    $inventory->save();
                }
            }

            $inventory = Inventory::where('CS_number', $request->cs_number)->first();

            if ($inventory) {
                $transaction->inventory_id = $inventory->id;
                $transaction->save();

                $inventory->CS_number_status = 'reserved';
                $inventory->status = 'reserved';
                $inventory->save();

                return response()->json([
                    'success' => true,
                    'message' => 'CS number successfully added'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'CS number not found'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating CS number: ' . $e->getMessage()
            ], 500);
        }
    }

}
