<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\Province;
use App\Models\Status;
use App\Models\Team;
use App\Models\Transactions;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return view('leads.leads');
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

            $inquiry = new Inquiry();
            $inquiry->users_id = Auth::id();
            $inquiry->customer_id = $customer->id;
            $inquiry->vehicle_id = $vehicle->id;
            $inquiry->transaction = $validated['transaction'];
            $inquiry->remarks = $validated['additional_info'];
            $inquiry->date = now()->format('F d'); // Month name day
            $inquiry->created_by = Auth::id();
            $inquiry->updated_by = Auth::id();
            $inquiry->transactional_status = in_array($validated['transaction'], ['cash', 'po']) ? 'approved' : 'pending';
            $inquiry->save();


            if($inquiry->transactional_status === 'approved'){
                // Add the inquiry_id to the transactions table
                $transaction = new Transactions();
                $transaction->inquiry_id = $inquiry->id; // Set the inquiry_id
                $transaction->status = Status::where('status', 'like', 'pending')->first()->id;
                $transaction->save(); // Save the transaction

                if (in_array($inquiry->transaction, ['cash', 'po'])) {

                    $approved_status = Status::where('status', 'like', 'approved')->first();
                    $application = new Application();
                    $application->customer_id = $customer->id;
                    $application->vehicle_id = $vehicle->id;
                    $application->transaction_id = $transaction->id;
                    $application->status_id = $approved_status->id;
                    $application->transaction = $inquiry->transaction;
                    $application->created_by = Auth::id();
                    $application->updated_by = Auth::id();
                    $application->save();

                    // Update the transaction table's application_id with the latest inserted application's id
                    $transaction->application_id = $application->id;
                    $transaction->status = Status::where('status', 'like', 'approved')->first()->id;
                    $transaction->application_transaction_date = now();
                    $transaction->transaction_updated_date = now();
                    $transaction->save();
                }

            }

            return response()->json([
                'success' => true,
                'message' => 'Inquiry created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request){

        // dd($request->start_date);
        $query = Inquiry::with([ 'user', 'customer', 'vehicle'])
                        ->whereNull('deleted_at')
                        ->where('transactional_status', '<>', 'approved');

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

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })
        

        ->make(true);
    }

    public function processing(Request $request){
        try {
            $inquiry = Inquiry::findOrFail(decrypt($request->id));
            $inquiry->transactional_status = 'approved';
            $inquiry->updated_by = Auth::user()->id;
            $inquiry->save();

            
            if (!in_array($inquiry->transaction, ['cash', 'po'])) {
                // Add the inquiry_id to the transactions table

                $pending_status = Status::where('status', 'like', 'pending')->first();
                $transaction = new Transactions();
                $transaction->inquiry_id = $inquiry->id; // Set the inquiry_id
                $transaction->status = $pending_status->id;
                $transaction->save(); // Save the transaction

                 
                 $application = new Application();
                 $application->customer_id = $inquiry->customer_id;
                 $application->vehicle_id = $inquiry->vehicle_id;
                 $application->transaction_id = $transaction->id;
                 $application->transaction = $inquiry->transaction;
                 $application->status_id = $pending_status->id;
                 $application->created_by = Auth::id();
                 $application->updated_by = Auth::id();
                 $application->save();

                 // Update the transaction table's application_id with the latest inserted application's id
                 $transaction->application_id = $application->id;
                 $transaction->status =  $application->status_id;
                 $transaction->application_transaction_date = now();
                 $transaction->transaction_updated_date = now();
                 $transaction->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Lead status updated to processing'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating lead status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(){
        try {
            $inquiry = Inquiry::findOrFail(decrypt(request()->id));
            $inquiry->updated_by = Auth::user()->id;
            $inquiry->save();
            $inquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lead successfully deleted'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting lead: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProvince(){
        $data = Province::all();

        return response()->json($data);
    }

    public function getUnit(){
        $data = Vehicle::select('unit')
        ->whereNull('deleted_at')
        ->groupBy('unit')
        ->get();


        return response()->json($data);
    }

    public function getVariantsAndColors(Request $request)
    {
        $unit = $request->input('unit');
        $vehicles = Vehicle::where('unit', $unit)
        ->get();

        $variants = $vehicles->pluck('variant')->unique()->values()->toArray();
        $colors = $vehicles->pluck('color')->unique()->values()->toArray();

        return response()->json([
            'variants' => $variants,
            'colors' => $colors,
        ]);
    }

    public function getVariants(Request $request)
    {
        $unit = $request->input('unit');
        $vehicles = Vehicle::where('unit', $unit)
        ->get();

        $variants = $vehicles->pluck('variant')->unique()->values()->toArray();
      

        return response()->json([
            'variants' => $variants,
        ]);
    }
    public function getColor(Request $request)
    {
        $unit = $request->input('unit');
        $variant = $request->input('variant');
        $vehicles = Vehicle::where('variant', $variant)
        ->get();

        $colors = $vehicles->pluck('color')->unique()->values()->toArray();
      
        return response()->json([
            'colors' => $colors,
        ]);
    }

    public function edit($id)
    {
        // Fetch the inquiry data by ID
       $decryptedId = decrypt($id);
       $data = Inquiry::with([ 'user', 'customer', 'vehicle'])->where('id', $decryptedId)->first();
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
    //             'province' => 'required',
    //         ]);

    //         $inquiry = Inquiry::findOrFail($id);
    //         $inquiry->customer_first_name = $validated['first_name'];
    //         $inquiry->customer_last_name = $validated['last_name'];
    //         $inquiry->contact_number = $validated['mobile_number'];
    //         $inquiry->gender = $validated['gender'];
    //         $inquiry->province_id = $validated['province'];
    //         $inquiry->unit = $validated['car_unit'];
    //         $inquiry->variant = $validated['car_variant'];
    //         $inquiry->color = $validated['car_color'];
    //         $inquiry->transaction = $validated['transaction'];
    //         $inquiry->age = $validated['age'];
    //         $inquiry->source = $validated['source'];
    //         $inquiry->remarks = $validated['additional_info'];
    //         $inquiry->updated_by = Auth::id();
    //         $inquiry->transactional_status = in_array($validated['transaction'], ['cash', 'po']) ? 'approved' : 'pending';
    //         $inquiry->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Inquiry updated successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating inquiry: ' . $e->getMessage()
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
            $inquiry = Inquiry::findOrFail($id);
            $customer = Customer::findOrFail($inquiry->customer_id);
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
            $inquiry->vehicle_id = $vehicle->id;
            $inquiry->transaction = $validated['transaction'];
            $inquiry->remarks = $validated['additional_info'];
            $inquiry->updated_by = Auth::id();
            $inquiry->transactional_status = in_array($validated['transaction'], ['cash', 'po']) ? 'approved' : 'pending';
            $inquiry->save();

            // Check and update transaction/application if transactional status changes to approved
            if ($inquiry->transactional_status === 'approved') {
                $transaction = Transactions::where('inquiry_id', $inquiry->id)->first();

                if (!$transaction) {
                    // Create a transaction if not exists
                    $transaction = new Transactions();
                    $transaction->inquiry_id = $inquiry->id;
                    $transaction->status = Status::where('status', 'like', 'pending')->first()->id;
                    $transaction->save();
                }

                if (in_array($inquiry->transaction, ['cash', 'po'])) {
                    $approved_status = Status::where('status', 'like', 'approved')->first();
                    $application = $transaction->application;

                    if (!$application) {
                        // Create a new application if not exists
                        $application = new Application();
                        $application->customer_id = $customer->id;
                        $application->vehicle_id = $vehicle->id;
                        $application->transaction_id = $transaction->id;
                        $application->status_id = $approved_status->id;
                        $application->transaction = $inquiry->transaction;
                        $application->created_by = Auth::id();
                        $application->updated_by = Auth::id();
                        $application->save();
                    }

                    // Update transaction with the application ID and approved status
                    $transaction->application_id = $application->id;
                    $transaction->status = $approved_status->id;
                    $transaction->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Inquiry updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

}
