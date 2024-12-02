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
use App\Models\InquryType;
use App\Models\BankTransaction;
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
        $query = Application::with(['user', 'customer', 'vehicle','status', 'bank', 'transactions'])
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

        ->addColumn('type', function($data) {
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;
                return $inquirytype;
            }
            return null;
        })

        ->addColumn('client_name', function($data) {
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;

                if($inquirytype === 'Individual'){
                    return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
                }else if($inquirytype === 'Fleet'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Company'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Government'){
                    return $data->customer->department_name; 
                } 
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
        
        ->addColumn('reservation_status', function($data) {
            $transaction = $data->transactions->first();
            return $transaction ? $transaction->reservation_status : 'N/A';
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_cancel(Request $request){

        // dd($request->start_date);
        $statusIds = Status::whereIn('status', ['Denied', 'Cancel'])->pluck('id')->toArray();
        $query = Application::with(['user', 'customer', 'vehicle','status', 'bank', 'transactions'])
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
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;
                return $inquirytype;
            }
            return null;
        })

        ->addColumn('client_name', function($data) {
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;

                if($inquirytype === 'Individual'){
                    return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
                }else if($inquirytype === 'Fleet'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Company'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Government'){
                    return $data->customer->department_name; 
                } 
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
        
        ->addColumn('reservation_status', function($data) {
            $transaction = $data->transactions->first();
            return $transaction ? $transaction->reservation_status : 'N/A';
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_cash(Request $request){

        // dd($request->start_date);
        $statusIds = Status::whereIn('status', ['Denied', 'Cancel', 'Processed', 'Approved', 'Reserved'])->pluck('id')->toArray();
        $query = Application::with(['user', 'customer', 'vehicle','status', 'bank', 'transactions'])
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
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;
                return $inquirytype;
            }
            return null;
        })

        ->addColumn('client_name', function($data) {
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;

                if($inquirytype === 'Individual'){
                    return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
                }else if($inquirytype === 'Fleet'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Company'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Government'){
                    return $data->customer->department_name; 
                } 
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

        ->addColumn('reservation_status', function($data) {
            $transaction = $data->transactions->first();
            return $transaction ? $transaction->reservation_status : 'N/A';
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function list_pending(Request $request){

        // dd($request->start_date);
        $pending_status = Status::where('status', 'like', 'pending')->first();
        $query = Application::with(['user', 'customer', 'vehicle','status', 'bank', 'transactions'])
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
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;
                return $inquirytype;
            }
            return null;
        })

        ->addColumn('client_name', function($data) {
            $firstTransaction = $data->transactions->first();
            if ($firstTransaction) {

                $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
                $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;

                if($inquirytype === 'Individual'){
                    return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
                }else if($inquirytype === 'Fleet'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Company'){
                    return $data->customer->company_name; 
                }else if($inquirytype === 'Government'){
                    return $data->customer->department_name; 
                } 
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
        
        ->addColumn('reservation_status', function($data) {
            $transaction = $data->transactions->first();
            return $transaction ? $transaction->reservation_status : 'N/A';
        })

        ->editColumn('date', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->make(true);
    }

    public function edit($id)
    {
        $decryptedId = decrypt($id);
        $data = Application::with(['user', 'customer', 'vehicle', 'status', 'bank', 'transactions'])
            ->where('id', $decryptedId)
            ->first();
        
        $firstTransaction = $data->transactions->first();
        if ($firstTransaction) {
            $inquiry = Inquiry::where('id', $firstTransaction->inquiry_id)->first();
            $inquirytype = InquryType::where('id', $inquiry->inquiry_type_id)->first()->inquiry_type;
        }

        $statuses = Status::all();
        $banks = Banks::all();

        return response()->json([
            'firstTransaction' => $firstTransaction,
            'inquiry' => $inquiry,
            'inquirytype' => $inquirytype,
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

            $transaction_id = Transactions::where('application_id', $application->id )->first();
            $inquiry_id = Inquiry::where('id', $transaction_id->inquiry_id)->first();

            $customer = Customer::findOrFail($application->customer_id);
            $inquiry = Inquiry::findOrFail($inquiry_id->id);

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

            Transactions::where('application_id', $application->id )
                ->whereNull('deleted_at')
                ->update([
                    'reservation_status' => $validated['payment_status'],
            ]);

         

            $inquiry->category = $validated['category'];
            $inquiry->quantity = $validated['quantity'];
            $inquiry->updated_by = Auth::id();
            $inquiry->updated_at = now();
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
            $application->updated_at = now();
            $application->save();

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
            $processing_status = Status::where('status', 'like', 'Processed')->first()->id;

            $application = Application::findOrFail(decrypt($request->id));

            if( $application->status_id == $pending_status || $application->status_id == $cancel_status ){
                $application->status_id =  $approved_status;
                $application->updated_by = Auth::user()->id;
                $application->updated_at = now();
                $application->save();

            }else if( $application->status_id == $approved_status){
                $transactions = Transactions::where('application_id', $application->id)
                ->whereNull('inventory_id')
                ->whereNull('deleted_at')
                ->get();

                foreach ($transactions as $transaction) {

                    // $inventory = Inventory::where('vehicle_id', $application->vehicle_id)
                    // ->where('CS_number_status', 'available')
                    // ->where('status', 'available')
                    // ->first();

                    // // Check if inventory is found
                    // if (!$inventory) {
                    //     return response()->json([
                    //         'success' => false,
                    //         'message' => 'Low in inventory for this vehicle.',
                    //     ], 400);
                    // }

                    $transaction = Transactions::findOrFail($transaction->id);
                    $transaction->status = $approved_status;
                    $transaction->reservation_id = Transactions::max('reservation_id') + 1;
                    $transaction->reservation_transaction_status = $pending_status;
                    $transaction->team_id = Auth::user()->team_id;
                    $transaction->save();

                }

                $application->status_id = $processing_status;
                $application->transaction = $application->transaction;
                $application->updated_by = Auth::id();
                $application->updated_at = now();
                $application->save();

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

        $banksArray = $request->bank_id;

        // Fetch all existing bank transactions for the application
        $existingBankTransactions = BankTransaction::where('application_id', decrypt($request->application_id))
            ->pluck('bank_id');

        // Check for banks that were removed from the array and soft delete them
        foreach ($existingBankTransactions as $existingBank) {
            if (!in_array($existingBank, $banksArray)) {
                BankTransaction::where('application_id', decrypt($request->application_id))
                    ->where('bank_id', $existingBank)
                    ->first()
                    ->delete();
            }
        }

        // Process each bank in the request
        foreach ($banksArray as $bank) {
            // Check if the bank already exists for the application
            if (!$existingBankTransactions->contains($bank)) {
                $bankTrans = new BankTransaction;
                $bankTrans->application_id = decrypt($request->application_id);
                $bankTrans->bank_id = $bank;
                $bankTrans->created_by = Auth::id();
                $bankTrans->updated_by = Auth::id();
                $bankTrans->save();
            }
        }

       

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

    public function getBanksForApplication($id)
    {
        try {
            $decryptedId = decrypt($id);
            
            // Get all bank transactions for this application
            $bankTransactions = BankTransaction::with('bank')
                ->where('application_id', $decryptedId)
                ->get()
                ->map(function($transaction) {
                    return [
                        'bank_id' => $transaction->bank_id,
                        'bank_name' => $transaction->bank->bank_name,
                        'approval_date' => $transaction->approval_date,
                        'is_preferred' => $transaction->is_preferred
                    ];
                });
            
            return response()->json([
                'success' => true,
                'banks' => $bankTransactions,
                'id' => $decryptedId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching banks: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBankApproval(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'bank_ids' => 'required|array',
                'bank_ids.*' => 'required|exists:banks,id',
                'approval_dates' => 'required|array',
                'approval_dates.*' => 'nullable|date',
                'preferred_bank' => 'required|exists:banks,id'
            ]);

            // Update approval dates and preferred status for each bank
            foreach ($validated['bank_ids'] as $index => $bankId) {
                BankTransaction::where('application_id', decrypt($id))
                ->where('bank_id', $bankId)
                    ->update([
                        'approval_date' => $validated['approval_dates'][$index],
                        'is_preferred' => $bankId == $validated['preferred_bank'],
                        'updated_by' => Auth::id()
                    ]);
            }

            $application = Application::findOrFail(decrypt($id));
            $application->bank_id = $validated['preferred_bank'];
            $application->save();

            return response()->json([
                'success' => true,
                'message' => 'Bank approval dates updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating bank approval dates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApplicationBanks($id)
    {
        try {
            $decryptedId = decrypt($id);
            
            // Get all bank transactions for this application
            $bankTransactions = BankTransaction::with('bank')
                ->where('application_id', $decryptedId)
                ->get()
                ->map(function($transaction) {
                    return [
                        'bank_id' => $transaction->bank_id,
                        'bank_name' => $transaction->bank->bank_name,
                        'approval_date' => $transaction->approval_date,
                        'is_preferred' => $transaction->is_preferred
                    ];
                });
            
            return response()->json([
                'success' => true,
                'banks' => $bankTransactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching banks: ' . $e->getMessage()
            ], 500);
        }
    }

}
