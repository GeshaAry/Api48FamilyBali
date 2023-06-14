<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Merchandise;
use App\Models\MerchandiseVariant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchandiseController extends Controller
{
    //mereturnkan semua data yang ada pada merchandise
    public function index(Request $request){
        $limit = $request->query('limit') ?? 100;
        $merchandise =  Merchandise::with(['MerchandiseCategory','MerchandiseVariant'])->paginate($limit);

        if(count($merchandise) > 0){
            return response([
                'message' => 'Retrieve All Merchandise Success',
                'data' => $merchandise
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada merchandise
    public function show($merchandise_id){
        $merchandise = Merchandise::with(['MerchandiseCategory','MerchandiseVariant'])->where('merchandise_id', $merchandise_id)->first();

        if(!is_null($merchandise)){
            return response([
                'message' => 'Retrieve Merchandise Success',
                'data' => $merchandise
            ], 200);
        }

        return response([
            'message' => 'Merchandise Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada merchandise
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'merchandisectg_id' => 'required',
            'merchandise_name' => 'required',
            'merchandise_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'merchandise_description' =>  'required',
            'merchandise_nameaccount' =>  'required',
            'merchandise_accountnumber' =>  'required',
            'merchandise_bankname' =>  'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(!empty($request->merchandise_picture)){
            $uploadPictureMerchandise = $request->merchandise_picture->store('img_merchandise', ['disk' => 'public']);
        }
        else{
            $uploadPictureMerchandise = NULL;
        }
        
        $merchandise = Merchandise::create([
            'merchandisectg_id' => $request->merchandisectg_id,
            'merchandise_name' => $request->merchandise_name,
            'merchandise_picture' => $uploadPictureMerchandise,
            'merchandise_description' => $request->merchandise_description,
            'merchandise_nameaccount' => $request->merchandise_nameaccount,
            'merchandise_accountnumber' => $request->merchandise_accountnumber,
            'merchandise_bankname' => $request->merchandise_bankname,
        ]);
        
        $merchandisevariants = $request->merchandise_variant;

        foreach($merchandisevariants as $variant){
            $merchandisevariant = json_decode($variant, true);
            $merchandisevar = MerchandiseVariant::create([
                'merchandise_id' => $merchandise->merchandise_id,
                'merchandisevar_size' => $merchandisevariant['merchandisevar_size'],
                'merchandisevar_price' => $merchandisevariant['merchandisevar_price'],
                'merchandisevar_stock' => $merchandisevariant['merchandisevar_stock'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ]);
        }

        return response([
            'message' => 'Add Merchandise Success',
            'data' => $merchandise
        ], 200);
    }


    //menghapus data pada merchandise
    public function destroy($merchandise_id){
        $merchandise = Merchandise::where('merchandise_id', $merchandise_id);

        if(is_null($merchandise)){
            return response([
                'message' => 'Merchandise Not Found',
                'date' => null
            ], 404);
        }

        if($merchandise->delete()){
            return response([
                'message' => 'Delete Merchandise Success',
                'data' => $merchandise
            ], 200);
        }

        return response([
            'message' => 'Delete Merchandise Failed',
            'data' => null,
        ], 400);
    }

    //update data pada Merchandise
    public function update(Request $request, $merchandise_id){
        $merchandise = Merchandise::where('merchandise_id', $merchandise_id)->first();

        if(is_null($merchandise)){
            return response([
                'message' => 'Merchandise Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'merchandisectg_id' => 'required',
            'merchandise_name' => 'required',
            'merchandise_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'merchandise_description' =>  'required',
            'merchandise_nameaccount' =>  'required',
            'merchandise_accountnumber' =>  'required',
            'merchandise_bankname' =>  'required'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $merchandise->merchandisectg_id = $updateData['merchandisectg_id'];
        $merchandise->merchandise_name = $updateData['merchandise_name'];
        if(isset($request->merchandise_picture)){
            $uploadPictureMerchandise = $request->merchandise_picture->store('img_merchandise', ['disk' => 'public']);
            $merchandise->merchandise_picture = $uploadPictureMerchandise;
        }
        $merchandise->merchandise_description = $updateData['merchandise_description'];
        $merchandise->merchandise_nameaccount = $updateData['merchandise_nameaccount'];
        $merchandise->merchandise_accountnumber = $updateData['merchandise_accountnumber'];
        $merchandise->merchandise_bankname = $updateData['merchandise_bankname'];

        $merchandise->MerchandiseVariant()->delete();

        $merchandisevariants = $request->merchandise_variant;

        foreach($merchandisevariants as $variant){
            $merchandisevariant = json_decode($variant, true);
            $merchandisevar = MerchandiseVariant::create([
                'merchandise_id' => $merchandise->merchandise_id,
                'merchandisevar_size' => $merchandisevariant['merchandisevar_size'],
                'merchandisevar_price' => $merchandisevariant['merchandisevar_price'],
                'merchandisevar_stock' => $merchandisevariant['merchandisevar_stock'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ]);
        }
        
        if($merchandise->save()){
            return response([
                'message' => 'Update Merchandise Success',
                'data' => $merchandise
            ], 200);
        }

        return response([
            'message' => 'Update Merchandise Failed',
            'data' => null
        ], 400);
    }
}
