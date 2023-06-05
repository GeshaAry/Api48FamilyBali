<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchandiseComment;
use Illuminate\Support\Facades\DB;

class MerchandiseCommentController extends Controller
{
    public function getMerchandiseComment(){
        $merchandisecomment = MerchandiseComment::all(); 

        if(count($merchandisecomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $merchandisecomment
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }
      public function index($merchandise_id){
        $merchandisecomment = MerchandiseComment::with(['User'])->whereMerchandiseId($merchandise_id)->get(); 

        if(count($merchandisecomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $merchandisecomment
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

   
    public function store(Request $request){
        $storeData = $request->all(); 
        $validate = Validator::make($storeData, [
            'merchandise_id' => 'required',
            'user_id' => 'required',
            'merchandise_comment' => 'required'
        ]); 
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $merchandisecomment = MerchandiseComment::create($storeData);

        return response([
            'message' => 'Add Comment Success',
            'data' => $merchandisecomment
        ], 200); 
    }

    
    public function destroy($id,$merchandisecmt_id){
        $merchandisecomment = MerchandiseComment::find($merchandisecmt_id); 

        if(is_null($merchandisecomment)){
            return response([
                'message' => 'Comment Not Found',
                'date' => null
            ], 404);
        } 

        if($merchandisecomment->delete()){
            return response([
                'message' => 'Delete Comment Success',
                'data' => $merchandisecomment
            ], 200);
        }

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null,
        ], 400);
    }

   
    public function update(Request $request, $id, $merchandisecmt_id){
        $merchandisecomment = MerchandiseComment::find($merchandisecmt_id);

        if(is_null($merchandisecomment)){
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'merchandise_id' => 'required',
            'user_id' => 'required',
            'merchandise_comment' => 'required'
        ]); 

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $merchandisecomment->merchandise_comment = $updateData['merchandise_comment']; 

        if($merchandisecomment->save()){
            return response([
                'message' => 'Update Comment Success',
                'data' => $merchandisecomment
            ], 200);
        } 

        return response([
            'message' => 'Update Comment Failed',
            'data' => null
        ], 400);
    }
}
