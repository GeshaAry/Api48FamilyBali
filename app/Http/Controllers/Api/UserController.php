<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //mereturnkan semua data yang ada pada user
    public function index(){
        $user = User::with(['Member'])->get();

        if(count($user) > 0){
            return response([
                'message' => 'Retrieve All User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada user
    public function show($user_id){
        $user = User::where('user_id', $user_id)->first();

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada user
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'member_id' => 'nullable',
            'user_email' =>  'required|email:rfc,dns|unique:users',
            'user_password' =>  'required|min:8',
            'user_name' =>  'required',
            'user_gender' =>  'required',
            'user_birthdate' =>  'required',
            'user_telephone' =>  'required',
            'user_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(!empty($request->user_picture)){
            $uploadPictureUser = $request->user_picture->store('img_user', ['disk' => 'public']);
        }
        else{
            $uploadPictureUser = NULL;
        }
        $user = User::create([
            'member_id' => $request->member_id,
            'user_email' => $request->user_email,
            'user_password' => bcrypt($request->user_password),
            'user_name' => $request->user_name,
            'user_gender' => $request->user_gender,
            'user_birthdate' => $request->user_birthdate,
            'user_telephone' => $request->user_telephone,
            'user_picture' => $uploadPictureUser,
        ]);

        return response([
            'message' => 'Add User Success',
            'data' => $user
        ], 200);
    }


    //menghapus data pada member
    public function destroy($user_id){
        $user = User::where('user_id', $user_id);

        if(is_null($user)){
            return response([
                'message' => 'user Not Found',
                'date' => null
            ], 404);
        }

        if($user->delete()){
            return response([
                'message' => 'Delete User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Delete User Failed',
            'data' => null,
        ], 400);
    }

    //update data pada User
    public function update(Request $request, $user_id){
        $user = User::where('user_id', $user_id)->first();

        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_id' => 'nullable',
            'user_email' =>  ['required', Rule::unique('users')->ignore($user)],
            'user_password' =>  'required|min:8',
            'user_name' =>  'required',
            'user_gender' =>  'required',
            'user_birthdate' =>  'required',
            'user_telephone' =>  'required',
            'user_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'     
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $user->member_id = $updateData['member_id'];
        $user->user_email = $updateData['user_email'];
        $user->user_password = $updateData['user_password'];
        $user->user_name = $updateData['user_name'];
        $user->user_gender = $updateData['user_gender'];
        $user->user_birthdate = $updateData['user_birthdate'];
        $user->user_telephone = $updateData['user_telephone'];
        if(isset($request->user_picture)){
            $uploadPictureUser = $request->user_picture->store('img_user', ['disk' => 'public']);
            $user->user_picture = $uploadPictureUser;
        }

       

        if($user->save()){
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }
}
