<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Customer;
use App\Models\Status;
use App\Models\Team;
use App\Models\Transactions;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return view('application.application');
        }else{
            return view('index');
        }

    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'age' => 'required|integer',
                'mobile_number' => 'required|string',
                'car_unit' => 'required|string',
                'car_variant' => 'required|string',
                'car_color' => 'required|string',
                'transaction' => 'required|string',
                'source' => 'required|string',
                'additional_info' => 'nullable|string',
                'gender' => 'required|string',
                'address' => 'required',
            ]);

            $customer = new Customer();
            $customer->customer_first_name = $validated['first_name'];
            $customer->customer_last_name = $validated['last_name'];
            $customer->contact_number = $validated['mobile_number'];
            $customer->gender = $validated['gender'];
            $customer->address = $validated['address'];
            $customer->age = $validated['age'];
            $customer->source = $validated['source'];
            $customer->created_by = Auth::id();
            $customer->updated_by = Auth::id();
            $customer->save();

            $vehicle = Vehicle::where('unit', $validated['car_unit'])
            ->where('variant', $validated['car_variant'])
            ->where('color',$validated['car_color'])
            ->first();

            $transaction = new Transactions();
            $transaction->status = Status::where('status', 'like', 'pending')->first()->id;
            $transaction->save(); // Save the transaction



            if (in_array($validated['transaction'], ['cash', 'po'])) {

                $approved_status = Status::where('status', 'like', 'approved')->first();
                $application = new Application();
                $application->customer_id = $customer->id;
                $application->vehicle_id = $vehicle->id;
                $application->transaction_id = $transaction->id;
                $application->status_id = $approved_status->id;
                $application->transaction = $validated['transaction'];
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();

                // Update the transaction table's application_id with the latest inserted application's id
                $transaction->application_id = $application->id;
                $transaction->status = Status::where('status', 'like', 'approved')->first()->id;
                $transaction->save();

            }else{

                $pending_status = Status::where('status', 'like', 'pending')->first();
                $application = new Application();
                $application->customer_id = $customer->id;
                $application->vehicle_id = $vehicle->id;
                $application->transaction_id = $transaction->id;
                $application->status_id = $pending_status->id;
                $application->transaction = $validated['transaction'];
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();
            }


            return response()->json([
                'success' => true,
                'message' => 'Application created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating application: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list_approved(Request $request){

        // dd($request->start_date);
        $status = Status::where('status', 'like', 'approved')->first();
        $query = Application::with([ 'user', 'customer', 'vehicle', 'trans'])
                        ->whereNull('deleted_at')
                        ->where('status_id', $status->id)
                        ;

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->user->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->addColumn('customer_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
        })

        ->addColumn('age', function($data) {
            return $data->customer->age;
        })

        ->addColumn('gender', function($data) {
            return $data->customer->gender;
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
        })

        ->addColumn('address', function($data) {
            return $data->customer->address;
        })

        ->addColumn('source', function($data) {
            return $data->customer->source;
        })

        ->addColumn('unit', function($data) {
            return $data->vehicle->unit;
        })

        ->addColumn('variant', function($data) {
            return $data->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->vehicle->color;
        })

        ->addColumn('transaction', function($data) {
            return $data->transaction;
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_cancel(Request $request){

        // dd($request->start_date);
        $statusIds = Status::whereIn('status', ['Denied', 'Cancel'])->pluck('id')->toArray();
        $query = Application::with(['user', 'customer', 'vehicle', 'trans'])
                        ->whereNull('deleted_at')
                        ->whereIn('status_id', $statusIds);

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->user->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->addColumn('customer_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
        })

        ->addColumn('age', function($data) {
            return $data->customer->age;
        })

        ->addColumn('gender', function($data) {
            return $data->customer->gender;
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
        })

        ->addColumn('address', function($data) {
            return $data->customer->address;
        })

        ->addColumn('source', function($data) {
            return $data->customer->source;
        })

        ->addColumn('unit', function($data) {
            return $data->vehicle->unit;
        })

        ->addColumn('variant', function($data) {
            return $data->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->vehicle->color;
        })

        ->addColumn('transaction', function($data) {
            return $data->transaction;
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_cash(Request $request){

        // dd($request->start_date);
        $statusIds = Status::whereIn('status', ['Denied', 'Cancel'])->pluck('id')->toArray();
        $query = Application::with(['user', 'customer', 'vehicle', 'trans'])
                        ->whereNull('deleted_at')
                        ->whereNotIn('status_id', $statusIds)
                        ->whereIn('transaction', ['cash', 'po']);

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->user->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->addColumn('customer_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
        })

        ->addColumn('age', function($data) {
            return $data->customer->age;
        })

        ->addColumn('gender', function($data) {
            return $data->customer->gender;
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
        })

        ->addColumn('address', function($data) {
            return $data->customer->address;
        })

        ->addColumn('source', function($data) {
            return $data->customer->source;
        })

        ->addColumn('unit', function($data) {
            return $data->vehicle->unit;
        })

        ->addColumn('variant', function($data) {
            return $data->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->vehicle->color;
        })

        ->addColumn('transaction', function($data) {
            return $data->transaction;
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_pending(Request $request){

        // dd($request->start_date);
        $pending_status = Status::where('status', 'like', 'pending')->first();
        $query = Application::with([ 'user', 'customer', 'vehicle', 'trans'])
                        ->whereNull('deleted_at')
                        ->where('status_id',$pending_status->id)
                        ;

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        return DataTables::of($list)
        ->addColumn('id', function($data) {
            return encrypt($data->id);
        })

        ->addColumn('team', function($data) {
            $team = Team::where('id',  $data->user->team_id)->first();
            return $team->name;
        })

        ->addColumn('agent', function($data) {
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->addColumn('customer_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
        })

        ->addColumn('age', function($data) {
            return $data->customer->age;
        })

        ->addColumn('gender', function($data) {
            return $data->customer->gender;
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
        })

        ->addColumn('address', function($data) {
            return $data->customer->address;
        })

        ->addColumn('source', function($data) {
            return $data->customer->source;
        })

        ->addColumn('unit', function($data) {
            return $data->vehicle->unit;
        })

        ->addColumn('variant', function($data) {
            return $data->vehicle->variant;
        })

        ->addColumn('color', function($data) {
            return $data->vehicle->color;
        })

        ->addColumn('transaction', function($data) {
            return $data->transaction;
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function edit($id)
    {
        // Fetch the Application data by ID
       $decryptedId = decrypt($id);
       $data = Application::with([ 'user', 'customer', 'vehicle', 'trans', 'status'])->where('id', $decryptedId)->first();
        return response()->json($data);
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'first_name' => 'required|string',
    //             'last_name' => 'required|string',
    //             'age' => 'required|integer',
    //             'mobile_number' => 'required|string',
    //             'car_unit' => 'required|string',
    //             'car_variant' => 'required|string',
    //             'car_color' => 'required|string',
    //             'transaction' => 'required|string',
    //             'source' => 'required|string',
    //             'additional_info' => 'nullable|string',
    //             'gender' => 'required|string',
    //             'address' => 'required',
    //         ]);

    //         // Fetch existing customer
    //         $customer = Customer::findOrFail($id);
    //         $customer->customer_first_name = $validated['first_name'];
    //         $customer->customer_last_name = $validated['last_name'];
    //         $customer->contact_number = $validated['mobile_number'];
    //         $customer->gender = $validated['gender'];
    //         $customer->address = $validated['address'];
    //         $customer->age = $validated['age'];
    //         $customer->source = $validated['source'];
    //         $customer->updated_by = Auth::id();
    //         $customer->save();

    //         // Fetch vehicle
    //         $vehicle = Vehicle::where('unit', $validated['car_unit'])
    //             ->where('variant', $validated['car_variant'])
    //             ->where('color', $validated['car_color'])
    //             ->first();

    //         if (!$vehicle) {
    //             throw new \Exception("Vehicle not found");
    //         }

    //         // Fetch application and transaction for the customer
    //         $application = Application::where('customer_id', $id)->firstOrFail();
    //         $transaction = Transactions::findOrFail($application->transaction_id);

    //         // Update transaction status
    //         if (in_array($validated['transaction'], ['cash', 'po'])) {
    //             $approved_status = Status::where('status', 'like', 'approved')->first();
    //             $transaction->status = $approved_status->id;
    //         } else {
    //             $pending_status = Status::where('status', 'like', 'pending')->first();
    //             $transaction->status = $pending_status->id;
    //         }
    //         $transaction->updated_at = now();
    //         $transaction->save();

    //         // Update application
    //         $application->vehicle_id = $vehicle->id;
    //         $application->status_id = $transaction->status;
    //         $application->transaction = $validated['transaction'];
    //         $application->updated_by = Auth::id();
    //         $application->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Application updated successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating application: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'age' => 'required|integer',
                'mobile_number' => 'required|string',
                'car_unit' => 'required|string',
                'car_variant' => 'required|string',
                'car_color' => 'required|string',
                'transaction' => 'required|string',
                'source' => 'required|string',
                'additional_info' => 'nullable|string',
                'gender' => 'required|string',
                'address' => 'required',
            ]);

            // Find the inquiry and related customer and vehicle
            $application = Application::findOrFail($id);
            $customer = Customer::findOrFail($application->customer_id);
            $vehicle = Vehicle::where('unit', $validated['car_unit'])
                ->where('variant', $validated['car_variant'])
                ->where('color', $validated['car_color'])
                ->first();

            // Update customer data
            $customer->customer_first_name = $validated['first_name'];
            $customer->customer_last_name = $validated['last_name'];
            $customer->contact_number = $validated['mobile_number'];
            $customer->gender = $validated['gender'];
            $customer->address = $validated['address'];
            $customer->age = $validated['age'];
            $customer->source = $validated['source'];
            $customer->updated_by = Auth::id();
            $customer->save();

            // Update inquiry data
            $application->vehicle_id = $vehicle->id;
            $application->transaction = $validated['transaction'];
            $application->remarks = $validated['additional_info'];
            $application->updated_by = Auth::id();
            $application->save();

        
            if (in_array($application->transaction, ['cash', 'po'])) {
                $transaction = Transactions::where('application_id', $application->id)->first();
                $approved_status = Status::where('status', 'like', 'approved')->first();

                $application->status_id = $approved_status->id;
                $application->transaction = $application->transaction;
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();

                $transactions = Transactions::findOrFail($transaction->id);
                $transactions->status = $approved_status->id;
                $transactions->save();
                
            }
            

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application: ' . $e->getMessage()
            ], 500);
        }
    }

}
