<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //mereturnkan semua data yang ada pada admin
    public function index(){
        $admin = Admin::all();

        if(count($admin) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $admin
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada admin
    public function show($admin_id){
        $admin = Admin::where('admin_id', $admin_id)->first();

        if(!is_null($admin)){
            return response([
                'message' => 'Retrieve Admin Success',
                'data' => $admin
            ], 200);
        }

        return response([
            'message' => 'Admin Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada admin
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'admin_username' => 'required|string',
            'admin_password' =>  'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $admin = Admin::create([
            'admin_username' => $request->admin_username,
            'admin_password' => bcrypt($request->admin_password)
        ]);

        return response([
            'message' => 'Add Admin Success',
            'data' => $admin
        ], 200);
    }


    //menghapus data pada admin
    public function destroy($admin_id){
        $admin = Admin::where('admin_id', $admin_id);

        if(is_null($admin)){
            return response([
                'message' => 'Admin Not Found',
                'date' => null
            ], 404);
        }

        if($admin->delete()){
            return response([
                'message' => 'Delete Admin Success',
                'data' => $admin
            ], 200);
        }

        return response([
            'message' => 'Delete Admin Failed',
            'data' => null,
        ], 400);
    }

    //update data pada admin
    public function update(Request $request, $admin_id){
        $admin = Admin::where('admin_id', $admin_id)->first();

        if(is_null($admin)){
            return response([
                'message' => 'Admin Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'admin_username' => 'required|string',
            'admin_password' => 'required',
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $admin->admin_username = $updateData['admin_username'];
        $admin->admin_password = $updateData['admin_password'];


        if($admin->save()){
            return response([
                'message' => 'Update Admin Success',
                'data' => $admin
            ], 200);
        }

        return response([
            'message' => 'Update Admin Failed',
            'data' => null
        ], 400);
    }
}
