<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Admin;
use App\Models\User;
use App\Models\TransactionEvent;
use Illuminate\Support\Facades\DB;
use PDF;

class TransactionEventController extends Controller
{
    //mereturnkan semua data yang ada pada transaction event
    public function index(){
        $transactionevent =  TransactionEvent::with(['User','Admin','Event'])->get();

        if(count($transactionevent) > 0){
            return response([
                'message' => 'Retrieve All Transaction Success',
                'data' => $transactionevent
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada transaction event
    public function show($user_id){
        $transactionevent = TransactionEvent::with(['User','Admin','Event'])->where('user_id', $user_id)->get();

        if(!is_null($transactionevent)){
            return response([
                'message' => 'Retrieve Transaction Event Success',
                'data' => $transactionevent
            ], 200);
        }

        return response([
            'message' => 'Transaction Event Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada transaction
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'user_id' => 'nullable',
            'event_id' =>  'nullable',
            'transactionevent_datebuy' =>  'nullable',
            'transactionevent_quantity' =>  'nullable',
            'transactionevent_totalprice' =>  'nullable',
            'transactionevent_status' =>  'nullable',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $event = Event::find($request->event_id);

        $eventticket = $event->event_ammountticket;

        if($request->transactionevent_quantity > $eventticket){
            return response([
                'message' => 'You input a quantity that is more than the available stock',
            ], 400);
        }
        $event->update([
            'event_ammountticket' => $eventticket - $request->transactionevent_quantity,
        ]);

        $transactionevent = TransactionEvent::create([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id,
            'transactionevent_datebuy' => $request->transactionevent_datebuy,
            'transactionevent_quantity' => $request->transactionevent_quantity,
            'transactionevent_totalprice' => $request->transactionevent_totalprice,
            'transactionevent_status' => $request->transactionevent_status,
        ]);

        return response([
            'message' => 'Add Transaction Success, please check ur profile to see ur transaction',
            'data' => $transactionevent
        ], 200);
    }


    //menghapus data pada transaction event
    public function destroy($transactionevent_id){
        $transactionevent = TransactionEvent::where('transactionevent_id', $transactionevent_id)->first();
        
        if(is_null($transactionevent)){
            return response([
                'message' => 'Transaction Event Not Found',
                'date' => null
            ], 404);
        }

        $event = Event::find($transactionevent->event_id);
        
        $eventticket = $event->event_ammountticket;

        $event->update([
            'event_ammountticket' => $eventticket + $transactionevent->transactionevent_quantity,
        ]);

        if($transactionevent->delete()){
            return response([
                'message' => 'Delete Transaction Success',
                'data' => $transactionevent
            ], 200);
        }

        return response([
            'message' => 'Delete Transaction Failed',
            'data' => null,
        ], 400);
    }

    //update data pada transaction event
    public function updateProofPayment(Request $request, $transactionevent_id){
        $transactionevent = TransactionEvent::where('transactionevent_id', $transactionevent_id)->first();

        if(is_null($transactionevent)){
            return response([
                'message' => 'Transaction Event Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'transactionevent_proofpayment' =>  'required|max:1024|mimes:jpg,png,jpeg|image',
        ]);
        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
        if(isset($request->transactionevent_proofpayment)){
            $uploadPictureTransactionEvent = $request->transactionevent_proofpayment->store('img_transactionevent', ['disk' => 'public']);
            $transactionevent->transactionevent_proofpayment = $uploadPictureTransactionEvent;
        }

        $transactionevent->transactionevent_status = 'On Progress';

        if($transactionevent->save()){
            return response([
                'message' => 'Update Proof Payment Success',
                'data' => $transactionevent
            ], 200);
        }

        return response([
            'message' => 'Update Proof Payment Failed',
            'data' => null
        ], 400);
    }

    //update status pada transaction event
    public function updateStatusTransactionEvent(Request $request, $transactionevent_id){
        $transactionevent = TransactionEvent::where('transactionevent_id', $transactionevent_id)->first();

        if(is_null($transactionevent)){
            return response([
                'message' => 'Transaction Event Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'admin_id' =>  'nullable',
            'transactionevent_status' =>  'nullable',
        ]);
        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $transactionevent->admin_id = $updateData['admin_id'];
        $transactionevent->transactionevent_status = $updateData['transactionevent_status'];

        if($transactionevent->save()){
            return response([
                'message' => 'Update Status Success',
                'data' => $transactionevent
            ], 200);
        }

        return response([
            'message' => 'Update Status Failed',
            'data' => null
        ], 400);
    }

    public function downloadEvent($transactionevent_id){
        $transactionevent = TransactionEvent::with(['User','Admin','Event'])->where('transactionevent_id', $transactionevent_id)->first();

        $data = [
            'title' => 'E-TICKET EVENT',
            'transaction' => $transactionevent,
        ];
          
        $pdf = PDF::loadView('invoiceevent', $data)->setPaper('a4', 'landscape');
    
        return $pdf->stream('Invoice E-Ticket.pdf');
    }

    public function reportEvent(Request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $transactionevent =  TransactionEvent::with(['User','Admin','Event'])->whereDate('transactionevent_datebuy','>=',$start_date)->whereDate('transactionevent_datebuy','<=',$end_date)->where('transactionevent_status','Transaction Success')->get();
        $total =  TransactionEvent::with(['User','Admin','Event'])->whereDate('transactionevent_datebuy','>=',$start_date)->whereDate('transactionevent_datebuy','<=',$end_date)->where('transactionevent_status','Transaction Success')->sum('transactionevent_totalprice');
        
        $data = [
            'title' => 'Report E-Ticket Event',
            'reports' => $transactionevent,
            'total' => $total,
        ];
          
        $pdf = PDF::loadView('reportevent', $data)->setPaper('a4');
    
        return $pdf->stream('Report E-Ticket.pdf');

    }

    public static function RupiahFormat($total_price){
        $result = number_format($total_price,0,'','.');
        return $result;
    }
}
