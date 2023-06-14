<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\ArticlePicture;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleController extends Controller
{
    //mereturnkan semua data yang ada pada article
    public function index(Request $request){
        $limit = $request->query('limit') ?? 100;
        $article = Article::with(['Admin', 'ArticlePictures'])->paginate($limit);

        if(count($article) > 0){
            return response([
                'message' => 'Retrieve All Article Success',
                'data' => $article
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada article
    public function show($article_id){
        $article = Article::with(['Admin','ArticlePictures'])->where('article_id', $article_id)->first();

        if(!is_null($article)){
            return response([
                'message' => 'Retrieve Article Success',
                'data' => $article
            ], 200);
        }

        return response([
            'message' => 'Article Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada Article
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'admin_id' => 'required',
            'article_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'article_description' =>  'required',
            'article_title' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(!empty($request->article_thumbnail)){
            $uploadPictureArticle = $request->article_thumbnail->store('img_article', ['disk' => 'public']);
        }
        else{
            $uploadPictureArticle = NULL;
        }

        $article = Article::create([
            'admin_id' => $request->admin_id,
            'article_thumbnail' => $uploadPictureArticle,
            'article_description' => $request->article_description,
            'article_title' => $request->article_title
        ]);

        if(!empty($request->article_pictures)){
            $articlephotos = $request->article_pictures;

            foreach($articlephotos as $articlephoto){

                $uploadPictureArticles = $articlephoto->store('img_articles', ['disk' => 'public']);

                $photos = ArticlePicture::create([
                    'article_id' => $article->article_id,
                    'article_picture' => $uploadPictureArticles,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(), 
                ]);
            }
        }

        return response([
            'message' => 'Add Article Success',
            'data' => $article
        ], 200);
    }


    //menghapus data pada article
    public function destroy($article_id){
        $article = Article::where('article_id', $article_id);

        if(is_null($article)){
            return response([
                'message' => 'Article Not Found',
                'date' => null
            ], 404);
        }

        if($article->delete()){
            return response([
                'message' => 'Delete Article Success',
                'data' => $article
            ], 200);
        }

        return response([
            'message' => 'Delete Article Failed',
            'data' => null,
        ], 400);
    }

    //update data pada Article
    public function update(Request $request, $article_id){
        $article = Article::where('article_id', $article_id)->first();

        if(is_null($article)){
            return response([
                'message' => 'Article Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'admin_id' => 'required',
            'article_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'article_description' =>  'required',
            'article_title' =>  'required',
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        
        $article->admin_id = $updateData['admin_id'];
        if(isset($request->article_thumbnail)){
            $uploadPictureArticle = $request->article_thumbnail->store('img_article', ['disk' => 'public']);
            $article->article_thumbnail = $uploadPictureArticle;
        }
        $article->article_description = $updateData['article_description'];
        $article->article_title = $updateData['article_title'];


        if(!empty($request->article_pictures)){
            $articlephotos = $request->article_pictures;

            foreach($articlephotos as $articlephoto){

                $uploadPictureArticles = $articlephoto->store('img_articles', ['disk' => 'public']);

                $photos = ArticlePicture::create([
                    'article_id' => $article->article_id,
                    'article_picture' => $uploadPictureArticles,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(), 
                ]);
            }
        }
    
        if($article->save()){
            return response([
                'message' => 'Update Article Success',
                'data' => $article
            ], 200);
        }

        return response([
            'message' => 'Update Article Failed',
            'data' => null
        ], 400);
    }
}
