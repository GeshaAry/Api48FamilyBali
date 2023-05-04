<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Validator;

class LoginAdminController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'username' => 'required',
            'password' => 'required'
        ]); 

        if(Admin::where('admin_username',$loginData['username'])->first()){
            $loginPegawai = Admin::where('admin_username', $loginData['username'])->first();

            $checkHashedPass = Admin::where('admin_username', $request->username)->first();
            $checkedPass = Hash::check($request->password, $checkHashedPass->admin_password);

            if($checkedPass){
                $data = Admin::where('admin_username', $request->username)->first();
                return response([
                    'message' => 'Login Success',
                    'data' => $data
                ]);
            }
        }
      
        return response([
            'message' => 'Login Failed, Check Email and Password again',
            'data' => null
        ], 404);
    

        if ($validate->fails())
            return response(['message' => $validate->error()], 400); //return error validasi input
    }
}
