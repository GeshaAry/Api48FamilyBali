<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Mail\UserEmail;
use App\Mail\ForgotPassword;
use Mail;
use Illuminate\Support\Facades\Hash;

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
        $user = User::with(['Member'])->where('user_id', $user_id)->first();

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

        $token = \Str::random(25);


        $user = User::create([
            'member_id' => $request->member_id,
            'user_email' => $request->user_email,
            'user_password' => bcrypt($request->user_password),
            'user_name' => $request->user_name,
            'user_gender' => $request->user_gender,
            'user_birthdate' => $request->user_birthdate,
            'user_telephone' => $request->user_telephone,
            'user_picture' => $uploadPictureUser,
            'token' => $token,
        ]);
        
        $this->sendEmail($request->user_email, $token);

        return response([
            'message' => 'Register Success, please check your email to verification first',
            'data' => $user
        ], 200);
    }

    public function sendEmail($email, $token){
        $mailData = [
            'title' => 'Verification Email',
            'body' => 'You have successfully registered on the 48 Family Bali website and verify your email by pressing the button below',
            'token' => $token
        ];

        Mail::to($email)->send(new UserEmail($mailData));
    }

    public function sendEmailForgotPassword($email, $token){
        $mailData = [
            'title' => 'Forgot Password',
            'body' => 'Change password by pressing the button below',
            'token' => $token,
            'email' => $email
        ];

        Mail::to($email)->send(new ForgotPassword($mailData));
    }

    public function emailVerify($token){
        $user = User::where('token', $token)->first();

        if(is_null($user)){
            return response([
                'message' => 'user Not Found',
                'date' => null
            ], 404);
        }

        $user->email_verified_at = now();
        $user->token = null;

        if($user->save()){
            return redirect()->to('http://localhost:8080/login');
        }

        return response([
            'message' => 'Verify Failed',
            'data' => null,
        ], 400);

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
            'user_name' =>  'required',
            'user_gender' =>  'required',
            'user_birthdate' =>  'required',
            'user_telephone' =>  'required',
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $user->user_name = $updateData['user_name'];
        $user->user_gender = $updateData['user_gender'];
        $user->user_birthdate = $updateData['user_birthdate'];
        $user->user_telephone = $updateData['user_telephone'];

       

        if($user->save()){
            return response([
                'message' => 'Update Profile Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update Profile Failed',
            'data' => null
        ], 400);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required'
        ]); 

        if(User::where('user_email',$loginData['email'])->first()){
            
            if(User::where('email_verified_at','!=',NULL)->where('user_email',$loginData['email'])->first()){

                $loginUser = User::where('user_email', $loginData['email'])->first();

                $checkHashedPass = User::where('user_email', $request->email)->first();
                $checkedPass = Hash::check($request->password, $checkHashedPass->user_password);

                if($checkedPass){
                    $data = User::where('user_email', $request->email)->first();
                    return response([
                        'message' => 'Login Success',
                        'data' => $data
                    ]);
                }
            }else{
                return response([
                    'message' => 'Email not Verification',
                    'data' => null
                ], 404);
            }
        }
      
        return response([
            'message' => 'Login Failed, Check Email and Password again',
            'data' => null
        ], 404);
    

        if ($validate->fails())
            return response(['message' => $validate->error()], 400); //return error validasi input
    }

    public function checkTokenResetPassword($email, $token_reset_password){
        $user = null;

        if($user = User::where('user_email', '=', $email)->where('token_reset_password', '=', $token_reset_password)->first()){
            return response([
                'message' => 'Get Token Success',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Get Token Failed',
            'data' => null
        ], 404);
    }

    public function resetPassword(Request $request, $email, $token_reset_password){
        $resetPassword = $request->all();

        $validate = Validator::make($resetPassword, [
            'new_password' => 'required',
            'new_confirm_password' => 'required|same:new_password',
        ]);

        if($validate->fails()){
            return response([
                'message' => 'Reset Password Failed',
                'data' => null
            ], 404);
        }

        $user = null;

        if($user = User::where('user_email', '=', $email)->where('token_reset_password', '=', $token_reset_password)->first()){
            $user->user_password = bcrypt($resetPassword['new_password']);
            $user->token_reset_password = null;
        } 

        if($user->save()){
            return response([
                'message' => 'Reset Password Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Reset Password Failed',
            'data' => null
        ], 404);
    }
    
    public function forgotPassword(Request $request){
        $forgotPassword = $request->all();

        $validate = Validator::make($forgotPassword, [
            'email' => 'required|email:rfc,dns',
        ]);

        if($validate->fails()){
            return response([
                'message' => 'Forgot Password Fail',
                'data' => null
            ], 404);
        }

        $token_reset_password = \Str::random(25);
        $user = null;

        if($user = User::where('user_email', '=', $forgotPassword['email'])->where('email_verified_at', '!=', null)->first()){
            $user->token_reset_password = $token_reset_password;

            $this->sendEmailForgotPassword($request->email, $token_reset_password);
        } 

        if($user->save()){
            return response([
                'message' => 'Sending Password Success',
                'data' => $user
            ]);
        }

        return response([
            'message' => 'Sending Password Success',
            'data' => $user
        ]);
    }

    public function updateProfilePictureUser(Request $request, $user_id){
        $user = User::where('user_id', $user_id)->first();

        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'user_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'  
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        if(isset($request->user_picture)){
            $uploadPictureUser = $request->user_picture->store('img_user', ['disk' => 'public']);
            $user->user_picture = $uploadPictureUser;
        }


        if($user->save()){
            return response([
                'message' => 'Update Picture Profile Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update Picture Profile Failed',
            'data' => null
        ], 400);
    }

    public function updateMemberUser(Request $request, $user_id){
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
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $user->member_id = $updateData['member_id'];

        if($user->save()){
            return response([
                'message' => 'Choose Member JKT48 Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Choose Member JKT48 Failed',
            'data' => null
        ], 400);
    }

    public function updatePassword(Request $request, $user_id){
        $data = User::find($user_id);

        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'password' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required',
        ]);

        $checkedPass = Hash::check($request->password, $data->user_password);
        if(!$checkedPass){
            return response([
                'message' => 'Password Wrong!',
                'data' => null
            ], 400);
        }

        $userData['user_password'] = Hash::make($request->newPassword);
        $data->update($userData);

        if($validator->fails()){
            return response([
                'message' => 'Change Password Failed',
            ], 400);
        }

        return response([
            'message' => 'Change Password Success',
            'data' => $data,
        ], 200);
    }

    
}
