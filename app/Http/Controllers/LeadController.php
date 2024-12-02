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
use App\Models\InquryType;
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

    public function individualList(Request $request){

        // dd($request->start_date);
        $status = Status::where('status', 'like', 'Processed')->first()->id;
        $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType'])
                        ->whereNull('deleted_at')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Individual');
                        })
                        ->where('status_id', '<>', $status);

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

        ->addColumn('client_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
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

        ->addColumn('status', function($data) {
            return $data->status->status;
        })

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })


        ->make(true);
    }

    public function fleetList(Request $request){

        // dd($request->start_date);
        $status = Status::where('status', 'like', 'Processed')->first()->id;
        $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType'])
                        ->whereNull('deleted_at')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Fleet');
                        })
                        ->where('status_id', '<>', $status);

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

        ->addColumn('client_name', function($data) {
            return $data->customer->company_name;
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

        ->addColumn('status', function($data) {
            return $data->status->status;
        })

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })


        ->make(true);
    }

    public function companyList(Request $request){

        // dd($request->start_date);
        $status = Status::where('status', 'like', 'Processed')->first()->id;
        $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType'])
                        ->whereNull('deleted_at')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Company');
                        })
                        ->where('status_id', '<>', $status);


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

        ->addColumn('client_name', function($data) {
            return $data->customer->company_name;
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

        ->addColumn('status', function($data) {
            return $data->status->status;
        })

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })


        ->make(true);
    }

    public function governmentList(Request $request){

        // dd($request->start_date);
        $status = Status::where('status', 'like', 'Processed')->first()->id;
        $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType'])
                        ->whereNull('deleted_at')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Government');
                        })
                        ->where('status_id', '<>', $status);


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

        ->addColumn('client_name', function($data) {
            return $data->customer->department_name;
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

        ->addColumn('status', function($data) {
            return $data->status->status;
        })

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })


        ->make(true);
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
       $data = Inquiry::with([ 'user', 'customer', 'vehicle', 'inquiryType', 'status'])->where('id', $decryptedId)->first();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'government' => 'nullable',
                'company' => 'nullable',
                'fleet' => 'nullable',
                'gender' => 'nullable',
                'age' => 'nullable',
                'mobile_number' => 'required|string',
                'car_unit' => 'required|string',
                'car_variant' => 'required|string',
                'car_color' => 'required|string',
                'transaction' => 'required|string',
                'source' => 'required|string',
                'additional_info' => 'nullable|string',
                'address' => 'required',
                'inquiry_type_id' => 'required',
                'category' => 'required',
                'quantity' => 'nullable',

            ]);

            $customer = new Customer();
            $customer->inquiry_type_id =  $validated['inquiry_type_id'];
            $customer->customer_first_name = $validated['first_name'];
            $customer->customer_last_name = $validated['last_name'];
            $customer->department_name = $validated['government'];
            $customer->company_name = $validated['company'] ?  $validated['company'] : $validated['fleet'];
            $customer->contact_number = $validated['mobile_number'];
            $customer->gender = $validated['gender'];
            $customer->address = $validated['address'];
            $customer->age = $validated['age'];
            $customer->source = $validated['source'];
            $customer->created_by = Auth::id();
            $customer->updated_by = Auth::id();
            $customer->save();

            $vehicle = Vehicle::firstOrCreate(
                [
                    'unit' => $validated['car_unit'],
                    'variant' => $validated['car_variant'],
                    'color' => $validated['car_color'],
                ],
                [
                    'unit' => $validated['car_unit'],
                    'variant' => $validated['car_variant'],
                    'color' => $validated['car_color'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );

            $approved_status = Status::where('status', 'like', 'approved')->first()->id;
            $pending_status = Status::where('status', 'like', 'pending')->first()->id;

            $inquiry = new Inquiry();
            $inquiry->inquiry_type_id =  $validated['inquiry_type_id'];
            $inquiry->customer_id = $customer->id;
            $inquiry->vehicle_id = $vehicle->id;
            $inquiry->quantity = $validated['quantity'];
            $inquiry->transaction = $validated['transaction'];
            $inquiry->category = $validated['category'];
            $inquiry->remarks = $validated['additional_info'];
            $inquiry->date = now()->format('F d'); // Month name day
            $inquiry->status_id = $pending_status;
            $inquiry->status_updated_by = Auth::id();
            $inquiry->status_updated_at = now();
            $inquiry->created_at = now();
            $inquiry->created_by = Auth::id();
            $inquiry->updated_by = Auth::id();
            $inquiry->save();


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

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'company' => 'nullable',
                'fleet' => 'nullable',
                'gender' => 'nullable',
                'age' => 'nullable',
                'mobile_number' => 'required|string',
                'car_unit' => 'required|string',
                'car_variant' => 'required|string',
                'car_color' => 'required|string',
                'transaction' => 'required|string',
                'source' => 'required|string',
                'additional_info' => 'nullable|string',
                'address' => 'required',
                'category' => 'required',
                'quantity' => 'nullable',
                'government' => 'nullable',
            ]);

            // Find the inquiry and related customer and vehicle
            $inquiry = Inquiry::findOrFail($id);
            $customer = Customer::findOrFail($inquiry->customer_id);

            $vehicle = Vehicle::firstOrCreate(
                [
                    'unit' => $validated['car_unit'],
                    'variant' => $validated['car_variant'],
                    'color' => $validated['car_color'],
                ],
                [
                    'unit' => $validated['car_unit'],
                    'variant' => $validated['car_variant'],
                    'color' => $validated['car_color'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );

            // Update customer data
            $customer->customer_first_name = $validated['first_name'];
            $customer->customer_last_name = $validated['last_name'];
            $customer->department_name = $validated['government'];
            $customer->company_name = $validated['company'] ?  $validated['company'] : $validated['fleet'];
            $customer->contact_number = $validated['mobile_number'];
            $customer->gender = $validated['gender'];
            $customer->address = $validated['address'];
            $customer->age = $validated['age'];
            $customer->source = $validated['source'];
            $customer->updated_by = Auth::id();
            $customer->updated_at = now();
            $customer->save();

            $approved_status = Status::where('status', 'like', 'approved')->first()->id;
            $pending_status = Status::where('status', 'like', 'pending')->first()->id;

            // Update inquiry data
            $inquiry->vehicle_id = $vehicle->id;
            $inquiry->transaction = $validated['transaction'];
            $inquiry->remarks = $validated['additional_info'];
            $inquiry->category = $validated['category'];
            $inquiry->quantity = $validated['quantity'];
            $inquiry->updated_by = Auth::id();
            $inquiry->updated_at = now();
            $inquiry->status_id = $pending_status;
            $inquiry->save();

            // Check and update transaction/application if transactional status changes to approved
            // if ($inquiry->status_id === $approved_status) {
            //     $transaction = Transactions::where('inquiry_id', $inquiry->id)->first();

            //     if (!$transaction) {
            //         // Create a transaction if not exists
            //         $transaction = new Transactions();
            //         $transaction->inquiry_id = $inquiry->id;
            //         $transaction->status =  $pending_status;
            //         $transaction->save();
            //     }

            //     if (in_array($inquiry->transaction, ['cash', 'po'])) {
            //         $application = $transaction->application;

            //         if (!$application) {
            //             // Create a new application if not exists
            //             $application = new Application();
            //             $application->customer_id = $customer->id;
            //             $application->vehicle_id = $vehicle->id;
            //             $application->transaction_id = $transaction->id;
            //             $application->status_id = $approved_status;
            //             $application->transaction = $inquiry->transaction;
            //             $application->created_by = Auth::id();
            //             $application->updated_by = Auth::id();
            //             $application->save();
            //         }

            //         // Update transaction with the application ID and approved status
            //         $transaction->application_id = $application->id;
            //         $transaction->status = $approved_status;
            //         $transaction->save();
            //     }
            // }

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

    public function processing(Request $request){
        try {

            $status = Status::where('status', 'like', 'Processed')->first()->id;
            $inquiry = Inquiry::findOrFail(decrypt($request->id));
            $inquiry->status_id =  $status;
            $inquiry->status_updated_by = Auth::user()->id;
            $inquiry->status_updated_at = now();
            $inquiry->save();


            if ($inquiry->status_id === $status ) {
                // Add the inquiry_id to the transactions table
                $pending_status = Status::where('status', 'like', 'pending')->first();

                $application = new Application();
                $application->customer_id = $inquiry->customer_id;
                $application->vehicle_id = $inquiry->vehicle_id;
                $application->transaction = $inquiry->transaction;
                $application->status_id = $pending_status->id;
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();
                // Update the transaction table's application_id with the latest inserted application's id
                if ($inquiry->quantity > 1) {
                    for ($i = 0; $i < $inquiry->quantity; $i++) {

                        $transaction = new Transactions();
                        $transaction->inquiry_id = $inquiry->id;
                        $transaction->status = $pending_status->id;
                        $transaction->application_id = $application->id;
                        $transaction->status =  $application->status_id;
                        $transaction->application_transaction_date = now();
                        $transaction->transaction_updated_date = now();
                        $transaction->save();
                    }
                }else{
                    
                    $transaction = new Transactions();
                    $transaction->inquiry_id = $inquiry->id;
                    $transaction->status = $pending_status->id;
                    $transaction->application_id = $application->id;
                    $transaction->status =  $application->status_id;
                    $transaction->application_transaction_date = now();
                    $transaction->transaction_updated_date = now();
                    $transaction->save();
                }
                    
            }

            return response()->json([
                'success' => true,
                'message' => 'Lead status updated to processed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating lead status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInquiryType(){
        $data = InquryType::all();
        return response()->json($data);
    }

    public function updateRemarks(Request $request){
        try {
            $inquiry = Inquiry::findOrFail($request->id);
            $inquiry->remarks = $request->remarks;
            $inquiry->save();

            return response()->json([
                'success' => true,
                'message' => 'Remarks updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating remarks: ' . $e->getMessage()
            ], 500);
        }
    }
}
