<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\ArticleComment;
use Illuminate\Support\Facades\DB;

class ArticleCommentController extends Controller
{
    public function getArticleComment(){
        $articlecomment = ArticleComment::all(); 

        if(count($articlecomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $articlecomment
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }
      public function index($article_id){
        $articlecomment = ArticleComment::with(['User'])->whereArticleId($article_id)->get(); 

        if(count($articlecomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $articlecomment
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
            'article_id' => 'required',
            'user_id' => 'required',
            'article_comment' => 'required'
        ]); 
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $articlecomment = ArticleComment::create($storeData);

        return response([
            'message' => 'Add Comment Success',
            'data' => $articlecomment
        ], 200); 
    }

    
    public function destroy($id, $articlecomment_id){
        $articlecomment = ArticleComment::find($articlecomment_id); 

        if(is_null($articlecomment)){
            return response([
                'message' => 'Comment Not Found',
                'date' => null
            ], 404);
        } 

        if($articlecomment->delete()){
            return response([
                'message' => 'Delete Comment Success',
                'data' => $articlecomment
            ], 200);
        }

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null,
        ], 400);
    }

   
    public function update(Request $request, $id, $articlecomment_id){
        $articlecomment = ArticleComment::find($articlecomment_id);

        if(is_null($articlecomment)){
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'article_id' => 'required',
            'user_id' => 'required',
            'article_comment' => 'required'
        ]); 

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $articlecomment->article_comment = $updateData['article_comment']; 

        if($articlecomment->save()){
            return response([
                'message' => 'Update Comment Success',
                'data' => $articlecomment
            ], 200);
        } 

        return response([
            'message' => 'Update Comment Failed',
            'data' => null
        ], 400);
    }
}
