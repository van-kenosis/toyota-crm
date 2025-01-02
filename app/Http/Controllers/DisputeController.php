<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Status;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DisputeController extends Controller
{
    public function index(){
        return view('dispute.dispute');
    }

    public function getDisputes(Request $request){
        $status = Status::where('status', 'like', 'Processed')->first()->id;

        if(Auth::user()->usertype->name === 'SuperAdmin'){
        $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType', 'updateBy'])
                        ->whereNull('deleted_at')
                        ->where('is_dispute', '1')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Individual');
                        })
                        ->where('status_id', '<>', $status);
        }elseif(Auth::user()->usertype->name === 'Group Manager'){
            $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType', 'updateBy'])
                        ->whereNull('deleted_at')
                        ->where('is_dispute', '1')
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Individual');
                        })
                        ->whereHas('user', function($subQuery) {
                            $subQuery->where('team_id', Auth::user()->team_id);
                        })
                        ->where('status_id', '<>', $status);
        }
        else{
            $query = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType', 'updateBy'])
                        ->whereNull('deleted_at')
                        ->where('is_dispute', '1')
                        ->where('created_by', Auth::user()->id)
                        ->whereHas('inquiryType', function($subQuery) {
                            $subQuery->where('inquiry_type', 'Individual');
                        })
                        ->where('status_id', '<>', $status);
        }



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

        ->addColumn('agent', function($data) {
            $inquiry = Inquiry::with([ 'user', 'customer', 'vehicle', 'status', 'inquiryType', 'updateBy'])
                        ->where('customer_id', $data->customer_id)
                        ->whereNull('deleted_at')
                        ->where('is_dispute', '0')
                        ->first();
            return $inquiry->user->first_name. ' ' .$inquiry->user->last_name;
        })

        ->addColumn('disputed_agent', function($data) {
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->addColumn('client_name', function($data) {
            return $data->customer->customer_first_name . ' ' . $data->customer->customer_last_name;
        })

        ->editColumn('created_at', function($data) {
            return $data->created_at->format('m/d/Y');
        })

        ->editColumn('updated_at', function($data) {
            return $data->updated_at->format('m/d/Y') ?? '';
        })

        ->editColumn('created_by', function($data){
            return $data->user->first_name . ' ' . $data->user->last_name;
        })

        ->editColumn('updated_by', function($data){
            return $data->updateBy->first_name . ' ' . $data->updateBy->last_name;
        })

        ->addColumn('status', function($data) {
            return $data->status->status;
        })


        ->make(true);
    }

    public function cancel(){
        try {
            $inquiry = Inquiry::findOrFail(decrypt(request()->id));
            $inquiry->updated_by = Auth::user()->id;
            $inquiry->save();
            $inquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'The dispute has been disapproved.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approved(){
        try {
            $inquiry = Inquiry::findOrFail(decrypt(request()->id));
            $inquiry->is_dispute = 0;
            $inquiry->updated_by = Auth::user()->id;
            $inquiry->save();

            return response()->json([
                'success' => true,
                'message' => 'The dispute has been approved.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


}
