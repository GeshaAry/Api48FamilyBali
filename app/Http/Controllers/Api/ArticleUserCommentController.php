<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\ArticleUserComment;
use Illuminate\Support\Facades\DB;

class ArticleUserCommentController extends Controller
{
    public function getArticleUserComment(){
        $articleusercomment = ArticleUserComment::all(); 

        if(count($articleusercomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $articleusercomment
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }
    public function index($articleuser_id){
        $articleusercomment = ArticleUserComment::with(['User'])->whereArticleuserId($articleuser_id)->get(); 

        if(count($articleusercomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $articleusercomment
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
            'articleuser_id' => 'required',
            'user_id' => 'required',
            'articleuser_comment' => 'required'
        ]); 
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $articleusercomment = ArticleUserComment::create($storeData);

        return response([
            'message' => 'Add Comment Success',
            'data' => $articleusercomment
        ], 200); 
    }

    
    public function destroy($id, $articleusercomment_id ){
        $articleusercomment = ArticleUserComment::find($articleusercomment_id); 

        if(is_null($articleusercomment)){
            return response([
                'message' => 'Comment Not Found',
                'date' => null
            ], 404);
        } 

        if($articleusercomment->delete()){
            return response([
                'message' => 'Delete Comment Success',
                'data' => $articleusercomment
            ], 200);
        }

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null,
        ], 400);
    }

   
    public function update(Request $request, $id, $articleusercomment_id){
        $articleusercomment = ArticleUserComment::find($articleusercomment_id);

        if(is_null($articleusercomment)){
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'articleuser_id' => 'required',
            'user_id' => 'required',
            'articleuser_comment' => 'required'
        ]); 

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $articleusercomment->articleuser_comment = $updateData['articleuser_comment']; 

        if($articleusercomment->save()){
            return response([
                'message' => 'Update Comment Success',
                'data' => $articleusercomment
            ], 200);
        } 

        return response([
            'message' => 'Update Comment Failed',
            'data' => null
        ], 400);
    }
}
