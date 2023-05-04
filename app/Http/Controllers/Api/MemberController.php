<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Memberjkt48;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    
    //mereturnkan semua data yang ada pada member
    public function index(){
        $member = Memberjkt48::all();

        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada member
    public function show($member_id){
        $member = Memberjkt48::where('member_id', $member_id)->first();

        if(!is_null($member)){
            return response([
                'message' => 'Retrieve Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Member JKT48 Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada admin
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'member_name' => 'required',
            'member_jiko' =>  'required',
            'member_show' =>  'required',
            'member_gen' =>  'required',
            'member_status' =>  'required',
            'member_birthdate' =>  'required',
            'member_blood' =>  'required',
            'member_horoskop' =>  'required',
            'member_height' =>  'required',
            'member_instagram' =>  'required',
            'member_twitter' =>  'required',
            'member_tiktok' =>  'required',
            'member_showroom' =>  'required',
            'member_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'            
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }


        if(!empty($request->member_picture)){
            $uploadPictureMember = $request->member_picture->store('img_member', ['disk' => 'public']);
        }
        else{
            $uploadPictureMember = NULL;
        }
        

        $member = Memberjkt48::create([
            'member_name' => $request->member_name,
            'member_jiko' => $request->member_jiko,
            'member_show' => $request->member_show,
            'member_gen' => $request->member_gen,
            'member_status' => $request->member_status,
            'member_birthdate' => $request->member_birthdate,
            'member_blood' => $request->member_blood,
            'member_horoskop' => $request->member_horoskop,
            'member_height' => $request->member_height,
            'member_instagram' => $request->member_instagram,
            'member_twitter' => $request->member_twitter,
            'member_tiktok' => $request->member_tiktok,
            'member_showroom' => $request->member_showroom,
            'member_picture' => $uploadPictureMember,
        ]);

        return response([
            'message' => 'Add Member JKT48 Success',
            'data' => $member
        ], 200);
    }


    //menghapus data pada member
    public function destroy($member_id){
        $member = Memberjkt48::where('member_id', $member_id);

        if(is_null($member)){
            return response([
                'message' => 'Member JKT48 Not Found',
                'date' => null
            ], 404);
        }

        if($member->delete()){
            return response([
                'message' => 'Delete Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Delete Member JKT48 Failed',
            'data' => null,
        ], 400);
    }

    //update data pada member
    public function update(Request $request, $member_id){
        $member = Memberjkt48::where('member_id', $member_id)->first();

        if(is_null($member)){
            return response([
                'message' => 'Member JKT48 Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_name' => 'required',
            'member_jiko' =>  'required',
            'member_show' =>  'required',
            'member_gen' =>  'required',
            'member_status' =>  'required',
            'member_birthdate' =>  'required',
            'member_blood' =>  'required',
            'member_horoskop' =>  'required',
            'member_height' =>  'required',
            'member_instagram' =>  'required',
            'member_twitter' =>  'required',
            'member_tiktok' =>  'required',
            'member_showroom' =>  'required',
            'member_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'     
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $member->member_name = $updateData['member_name'];
        $member->member_jiko = $updateData['member_jiko'];
        $member->member_show = $updateData['member_show'];
        $member->member_gen = $updateData['member_gen'];
        $member->member_status = $updateData['member_status'];
        $member->member_birthdate = $updateData['member_birthdate'];
        $member->member_blood = $updateData['member_blood'];
        $member->member_horoskop = $updateData['member_horoskop'];
        $member->member_height = $updateData['member_height'];
        $member->member_instagram = $updateData['member_instagram'];
        $member->member_twitter = $updateData['member_twitter'];
        $member->member_tiktok = $updateData['member_tiktok'];
        $member->member_showroom = $updateData['member_showroom'];
        if(isset($request->member_picture)){
            $uploadPictureMember = $request->member_picture->store('img_member', ['disk' => 'public']);
            $member->member_picture = $uploadPictureMember;
        }

       

        if($member->save()){
            return response([
                'message' => 'Update Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Update Member JKT48 Failed',
            'data' => null
        ], 400);
    }
}
