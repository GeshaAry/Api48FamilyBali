<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\User;
use App\Models\TransactionMerchandise;
use App\Models\MerchandiseVariant;
use App\Models\Merchandise;
use Illuminate\Support\Facades\DB;
use PDF;

class TransactionMerchandiseController extends Controller
{

     //mereturnkan semua data yang ada pada transaction merchandise
     public function index(){
        $transactionmerchandise = TransactionMerchandise::with(['User','Admin','MerchandiseVariant.Merchandise'])->get();

        if(count($transactionmerchandise) > 0){
            return response([
                'message' => 'Retrieve All Transaction Success',
                'data' => $transactionmerchandise
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada transaction merchandise
    public function show($user_id){
        $transactionmerchandise = TransactionMerchandise::with(['User','Admin','MerchandiseVariant.Merchandise'])->where('user_id', $user_id)->get();

        if(!is_null($transactionmerchandise)){
            return response([
                'message' => 'Retrieve Transaction Merchandise Success',
                'data' => $transactionmerchandise
            ], 200);
        }

        return response([
            'message' => 'Transaction Merchandise Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada transaction
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'user_id' => 'nullable',
            'merchandisevar_id' =>  'required',
            'merchandisetns_datebuy' =>  'nullable',
            'merchandisetns_totalprice' =>  'nullable',
            'merchandisetns_status' =>  'nullable',
            'merchandisetns_quantity' =>  'nullable',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $merchandisevar = MerchandiseVariant::find($request->merchandisevar_id);

        $merchandisestock = $merchandisevar->merchandisevar_stock;

        if($request->merchandisetns_quantity > $merchandisestock){
            return response([
                'message' => 'You input a quantity that is more than the available stock',
            ], 400);
        }

        $merchandisevar->update([
            'merchandisevar_stock' => $merchandisestock - $request->merchandisetns_quantity,
        ]);

        $transactionmerchandise = TransactionMerchandise::create([
            'user_id' => $request->user_id,
            'merchandisevar_id' => $request->merchandisevar_id,
            'merchandisetns_datebuy' => $request->merchandisetns_datebuy,
            'merchandisetns_totalprice' => $request->merchandisetns_totalprice,
            'merchandisetns_status' => $request->merchandisetns_status,
            'merchandisetns_quantity' => $request->merchandisetns_quantity,
        ]);

        return response([
            'message' => 'Add Transaction Success, please check ur profile to see ur transaction',
            'data' => $transactionmerchandise
        ], 200);
    }


    //menghapus data pada transaction merchandise
    public function destroy($merchandisetns_id){
        $transactionmerchandise = TransactionMerchandise::where('merchandisetns_id', $merchandisetns_id)->first();
        
        if(is_null($transactionmerchandise)){
            return response([
                'message' => 'Transaction Merchandise Not Found',
                'date' => null
            ], 404);
        }

        $merchandisevar = MerchandiseVariant::find($transactionmerchandise->merchandisevar_id);

        $merchandisestock = $merchandisevar->merchandisevar_stock;

        $merchandisevar->update([
            'merchandisevar_stock' => $merchandisestock + $transactionmerchandise->merchandisetns_quantity,
        ]);

        if($transactionmerchandise->delete()){
            return response([
                'message' => 'Delete Transaction Success',
                'data' => $transactionmerchandise
            ], 200);
        }

        return response([
            'message' => 'Delete Transaction Failed',
            'data' => null,
        ], 400);
    }

    //update data pada transaction merchandise
    public function updateProofPayment(Request $request, $merchandisetns_id){
        $transactionmerchandise = TransactionMerchandise::where('merchandisetns_id', $merchandisetns_id)->first();

        if(is_null($transactionmerchandise)){
            return response([
                'message' => 'Transaction Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'merchandisetns_proofpayment' =>  'required|max:1024|mimes:jpg,png,jpeg|image',
        ]);
        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }
        if(isset($request->merchandisetns_proofpayment)){
            $uploadPictureTransactionMerchandise = $request->merchandisetns_proofpayment->store('img_transactionmerchandise', ['disk' => 'public']);
            $transactionmerchandise->merchandisetns_proofpayment = $uploadPictureTransactionMerchandise;
        }

        $transactionmerchandise->merchandisetns_status = 'On Progress';

        if($transactionmerchandise->save()){
            return response([
                'message' => 'Update Proof Payment Success',
                'data' => $transactionmerchandise
            ], 200);
        }

        return response([
            'message' => 'Update Proof Payment Failed',
            'data' => null
        ], 400);
    }

    //update status pada transaction merchandise
    public function updateStatusTransactionMerchandise(Request $request, $merchandisetns_id){
        $transactionmerchandise = TransactionMerchandise::where('merchandisetns_id', $merchandisetns_id)->first();

        if(is_null($transactionmerchandise)){
            return response([
                'message' => 'Transaction Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'admin_id' =>  'nullable',
            'merchandisetns_status' =>  'nullable',
        ]);
        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $transactionmerchandise->admin_id = $updateData['admin_id'];
        $transactionmerchandise->merchandisetns_status = $updateData['merchandisetns_status'];

        if($transactionmerchandise->save()){
            return response([
                'message' => 'Update Status Success',
                'data' => $transactionmerchandise
            ], 200);
        }

        return response([
            'message' => 'Update Status Failed',
            'data' => null
        ], 400);
    }

    public function downloadMerchandise($merchandisetns_id){
        $transactionmerchandise = TransactionMerchandise::with(['User','Admin','MerchandiseVariant'])->where('merchandisetns_id', $merchandisetns_id)->first();

        $data = [
            'title' => 'MERCHANDISE',
            'transaction' => $transactionmerchandise,
        ];
          
        $pdf = PDF::loadView('invoicemerchandise', $data)->setPaper('a4', 'landscape');
    
        return $pdf->stream('Invoice Merchandise.pdf');
    }

    public function reportMerchandise(Request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $transactionmerchandise =  TransactionMerchandise::with(['User','Admin','MerchandiseVariant.Merchandise'])->whereDate('merchandisetns_datebuy','>=',$start_date)->whereDate('merchandisetns_datebuy','<=',$end_date)->where('merchandisetns_status','Transaction Success')->get();
        $total =  TransactionMerchandise::with(['User','Admin','MerchandiseVariant.Merchandise'])->whereDate('merchandisetns_datebuy','>=',$start_date)->whereDate('merchandisetns_datebuy','<=',$end_date)->where('merchandisetns_status','Transaction Success')->sum('merchandisetns_totalprice');
        
        $data = [
            'title' => 'Report Merchandise',
            'reports' => $transactionmerchandise,
            'total' => $total,
        ];
          
        $pdf = PDF::loadView('reportmerchandise', $data)->setPaper('a4', 'landscape');
    
        return $pdf->stream('Report Merchandise.pdf');

    }

    public static function RupiahFormat($total_price){
        $result = number_format($total_price,0,'','.');
        return $result;
    }
}
