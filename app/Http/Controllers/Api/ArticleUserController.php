<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\ArticleUser;
use Illuminate\Support\Facades\DB;

class ArticleUserController extends Controller
{
    //mereturnkan semua data yang ada pada article user
    public function index(Request $request){

        $limit = $request->query('limit') ?? 100;
        $articleuser = ArticleUser::with(['User'])->paginate($limit); 


        if(count($articleuser) > 0){
            return response([
                'message' => 'Retrieve All Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showArticleUser(Request $request){

        $limit = $request->query('limit') ?? 100;
        $articleuser = ArticleUser::with(['User'])->whereArticleuserStatus('Article Accepted')->paginate($limit); 


        if(count($articleuser) > 0){
            return response([
                'message' => 'Retrieve All Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }


    
    //mereturnkan data yang dipilih pada article user
    public function show($user_id){
        $articleuser = ArticleUser::with(['User'])->where('user_id', $user_id)->get();

        if(!is_null($articleuser)){
            return response([
                'message' => 'Retrieve Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Article User Not Found',
            'data' => null
        ], 400);
    }

    //mereturnkan data yang dipilih pada article user
    public function showDetailArticle($articleuser_id){
        $articleuser = ArticleUser::with(['User'])->where('articleuser_id', $articleuser_id)->first();

        if(!is_null($articleuser)){
            return response([
                'message' => 'Retrieve Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Article User Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada Article user
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'user_id' => 'required',
            'articleuser_thumbnail' => 'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'articleuser_description' => 'required',
            'articleuser_title' =>  'required',
            'articleuser_status' =>  'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(!empty($request->articleuser_thumbnail)){
            $uploadPictureArticleUser = $request->articleuser_thumbnail->store('img_articleuser', ['disk' => 'public']);
        }
        else{
            $uploadPictureArticleUser = NULL;
        }
        $articleuser = ArticleUser::create([
            'user_id' => $request->user_id,
            'articleuser_thumbnail' => $uploadPictureArticleUser,
            'articleuser_description' => $request->articleuser_description,
            'articleuser_title' => $request->articleuser_title,
            'articleuser_status' => $request->articleuser_status
        ]);

        return response([
            'message' => 'Add Article User Success',
            'data' => $articleuser
        ], 200);
    }


    //menghapus data pada article user
    public function destroy($articleuser_id){
        $articleuser = ArticleUser::where('articleuser_id', $articleuser_id);

        if(is_null($articleuser)){
            return response([
                'message' => 'Article User Not Found',
                'date' => null
            ], 404);
        }

        if($articleuser->delete()){
            return response([
                'message' => 'Delete Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Delete Article User Failed',
            'data' => null,
        ], 400);
    }

    //update data pada Article
    public function update(Request $request, $articleuser_id){
        $articleuser = ArticleUser::where('articleuser_id', $articleuser_id)->first();

        if(is_null($articleuser)){
            return response([
                'message' => 'Article User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'user_id' => 'required',
            'articleuser_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'articleuser_description' =>  'required',
            'articleuser_title' =>  'required',
            'articleuser_status' =>  'required'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $articleuser->user_id = $updateData['user_id'];
        if(isset($request->articleuser_thumbnail)){
            $uploadPictureArticleUser = $request->articleuser_thumbnail->store('img_articleuser', ['disk' => 'public']);
            $articleuser->articleuser_thumbnail = $uploadPictureArticleUser;
        }
        $articleuser->articleuser_description = $updateData['articleuser_description'];
        $articleuser->articleuser_title = $updateData['articleuser_title'];
        $articleuser->articleuser_status = $updateData['articleuser_status'];
    
        if($articleuser->save()){
            return response([
                'message' => 'Update Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Update Article User Failed',
            'data' => null
        ], 400);
    }

    //update status pada article user
    public function updateStatus(Request $request, $articleuser_id){
        $articleuser = ArticleUser::where('articleuser_id', $articleuser_id)->first();

        if(is_null($articleuser)){
            return response([
                'message' => 'Article User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'articleuser_status' =>  'required'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $articleuser->articleuser_status = $updateData['articleuser_status'];
    
        if($articleuser->save()){
            return response([
                'message' => 'Update Status Article User Success',
                'data' => $articleuser
            ], 200);
        }

        return response([
            'message' => 'Update Status Article User Success',
            'data' => null
        ], 400);
    }
}
