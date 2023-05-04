<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchandiseCategory;
use Illuminate\Support\Facades\DB;

class MerchandiseCategoryController extends Controller
{
    //mereturnkan semua data yang ada pada merchandise category
    public function index(){ 
        $merchandisectg = MerchandiseCategory::all();

        if(count($merchandisectg) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $merchandisectg
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada merchandise category
    public function show($merchandisectg_id){
        $merchandisectg = MerchandiseCategory::where('merchandisectg_id', $merchandisectg_id)->first();

        if(!is_null($merchandisectg)){
            return response([
                'message' => 'Retrieve Merchandise Category Success',
                'data' => $merchandisectg
            ], 200);
        }

        return response([
            'message' => 'Merchandise Category Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada Merchandise Category
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'merchandisectg_name' => 'required|'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $merchandisectg = MerchandiseCategory::create([
            'merchandisectg_name' => $request->merchandisectg_name
        ]);

        return response([
            'message' => 'Add Merchandise Category Success',
            'data' => $merchandisectg
        ], 200);
    }


    //menghapus data pada merchandise category
    public function destroy($merchandisectg_id){
        $merchandisectg = MerchandiseCategory::where('merchandisectg_id', $merchandisectg_id);

        if(is_null($merchandisectg)){
            return response([
                'message' => 'Merchandise Category Not Found',
                'date' => null
            ], 404);
        }

        if($merchandisectg->delete()){
            return response([
                'message' => 'Delete Merchandise Category Success',
                'data' => $merchandisectg
            ], 200);
        }

        return response([
            'message' => 'Delete Merchandise Category Failed',
            'data' => null,
        ], 400);
    }

    //update data pada merchandise category
    public function update(Request $request, $merchandisectg_id){
        $merchandisectg = MerchandiseCategory::where('merchandisectg_id', $merchandisectg_id)->first();

        if(is_null($merchandisectg)){
            return response([
                'message' => 'Merchandise Category Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'merchandisectg_name' => 'required'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $merchandisectg->merchandisectg_name = $updateData['merchandisectg_name'];


        if($merchandisectg->save()){
            return response([
                'message' => 'Update Merchandise Category Success',
                'data' => $merchandisectg
            ], 200);
        }

        return response([
            'message' => 'Update Merchandise Category Failed',
            'data' => null
        ], 400);
    }
}
