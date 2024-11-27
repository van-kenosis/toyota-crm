<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Customer;
use App\Models\Status;
use App\Models\Team;
use App\Models\Transactions;
use App\Models\Vehicle;
use App\Models\Banks;
use App\Models\Inquiry;
use App\Models\Inventory;
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
                'bank_id' => 'required',
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
                $application->bank_id = $validated['bank_id'];
                $application->transaction = $validated['transaction'];
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();

                // Update the transaction table's application_id with the latest inserted application's id
                $transaction->application_id = $application->id;
                $transaction->status = Status::where('status', 'like', 'approved')->first()->id;
                $transaction->application_transaction_date = now();
                $transaction->transaction_updated_date = now();
                $transaction->save();

            }else{

                $pending_status = Status::where('status', 'like', 'pending')->first();
                $application = new Application();
                $application->customer_id = $customer->id;
                $application->vehicle_id = $vehicle->id;
                $application->transaction_id = $transaction->id;
                $application->status_id = $pending_status->id;
                $application->bank_id = $validated['bank_id'];
                $application->transaction = $validated['transaction'];
                $application->created_by = Auth::id();
                $application->updated_by = Auth::id();
                $application->save();

                $transaction->application_id = $application->id;
                $transaction->status = $pending_status->id;
                $transaction->application_transaction_date = now();
                $transaction->transaction_updated_date = now();
                $transaction->save();

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
        $query = Application::with(['user', 'customer', 'vehicle', 'trans', 'status', 'bank', 'inquiry'])
                        ->whereNull('deleted_at')
                        ->whereNotIn('transaction', ['cash', 'po'])
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

        ->addColumn('type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->customer->department_name; 
            } 
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
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
        $query = Application::with(['user', 'customer', 'vehicle', 'trans', 'status', 'bank', 'inquiry'])
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

        ->addColumn('type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->customer->department_name; 
            } 
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
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
        $query = Application::with(['user', 'customer', 'vehicle', 'trans', 'status', 'bank', 'inquiry'])
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

        ->addColumn('type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->customer->department_name; 
            } 
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
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
        $query = Application::with(['user', 'customer', 'vehicle', 'trans', 'status', 'bank', 'inquiry'])
                        ->whereNull('deleted_at')
                        ->whereNotIn('transaction', ['cash', 'po'])
                        ->where('status_id', $pending_status->id);

        if ($request->has('date_range') && !empty($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $list = $query->get();

        // dd($list->toArray());

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

        ->addColumn('type', function($data) {
            return $data->inquiry->inquiryType->inquiry_type;
        })

        ->addColumn('client_name', function($data) {
            if($data->inquiry->inquiryType->inquiry_type === 'Individual'){
                return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
            }else if($data->inquiry->inquiryType->inquiry_type === 'Fleet'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Company'){
                return $data->customer->company_name; 
            }else if($data->inquiry->inquiryType->inquiry_type === 'Government'){
                return $data->customer->department_name; 
            } 
        })

        ->addColumn('contact_number', function($data) {
            return $data->customer->contact_number;
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
        $data = Application::with(['user', 'customer', 'vehicle', 'trans', 'status', 'bank', 'inquiry'])
            ->where('id', $decryptedId)
            ->first();
            $statuses = Status::all();
            $banks = Banks::all();
    
            return response()->json([
                'application' => $data,
                'statuses' => $statuses,
                'banks' => $banks
            ]);
    }

    public function update(Request $request, $id)
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
                'category' => 'required',
                'quantity' => 'nullable',
                'payment_status' => 'nullable',
            ]);

            // Find the inquiry and related customer and vehicle
            $application = Application::findOrFail($id);
            $customer = Customer::findOrFail($application->customer_id);
            $transaction = Transactions::findorfail($application->transaction_id);
            $inquiry = Inquiry::findorfail($transaction->inquiry_id);
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

            $inquiry->category = $validated['category'];
            $inquiry->quantity = $validated['quantity'];
            $inquiry->save();

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

            // Update inquiry data
            $application->vehicle_id = $vehicle->id;
            $application->transaction = $validated['transaction'];
            $application->remarks = $validated['additional_info'];
            $application->updated_by = Auth::id();
            $application->save();

           

            $transaction->reservation_status = $validated['payment_status'];
            $transaction->save();

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

    public function getBanks(){
        $data = Banks::all();
        return response()->json($data);
    }

    public function getStatus(){
        $data = Status::all();
        return response()->json($data);
    }

    public function processing(Request $request){
        try {

            $approved_status = Status::where('status', 'like', 'approved')->first()->id;
            $pending_status = Status::where('status', 'like', 'pending')->first()->id;
            $cancel_status = Status::where('status', 'like', 'cancel')->first()->id;
            $processing_status = Status::where('status', 'like', 'Processing')->first()->id;

            $application = Application::findOrFail(decrypt($request->id));

            if( $application->status_id == $pending_status || $application->status_id == $cancel_status ){
                $application->status_id =  $approved_status;
                $application->updated_by = Auth::user()->id;
                $application->updated_at = now();
                $application->save();

            }else if( $application->status_id == $approved_status){

                $inventory = Inventory::where('vehicle_id', $application->vehicle_id)
                ->where('CS_number_status', 'available')
                ->first();

                if ($inventory) {
                    $transaction = Transactions::with('inquiry')->where('application_id', $application->id)->first();
    
                    $application->status_id = $processing_status;
                    $application->transaction = $application->transaction;
                    $application->updated_by = Auth::id();
                    $application->updated_at = now();
                    $application->save();

                    $transactions = Transactions::findOrFail($transaction->id);
                    $transactions->status = $approved_status;
                    $transactions->reservation_id = Transactions::max('reservation_id') + 1;
                    $transactions->reservation_transaction_status = $pending_status;
                    $transactions->reservation_date = now();
                    $transactions->inventory_id = $inventory->id;
                    $transactions->team_id = Auth::user()->team_id;
                    $transactions->save();

                    $invt = Inventory::findOrFail($inventory->id);
                    $invt->status = 'reserved';
                    $invt->CS_number_status = 'reserved';
                    $invt->updated_at = now();
                    $invt->save();

                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'No Data Found ' 
                    ], 500);
                }

            }

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request){
        try {

            $cancel_status = Status::where('status', 'like', 'cancel')->first()->id;

            $application = Application::findOrFail(decrypt($request->id));
            $application->status_id =  $cancel_status;
            $application->updated_by = Auth::user()->id;
            $application->updated_at = now();
            $application->save();

            return response()->json([
                'success' => true,
                'message' => 'Application has been canceled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBanks(Request $request){
        try {
        $application = Application::findOrFail(decrypt($request->application_id));
        $application->bank_id =json_encode($request->bank_id);
        $application->save();

        return response()->json([
            'success' => true,
            'message' => 'Banks updated successfully'
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating banks: ' . $e->getMessage()
            ], 500);
        }

    }
    


}
