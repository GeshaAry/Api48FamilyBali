<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\VideoGallery;
use Illuminate\Support\Facades\DB;

class VideoGalleryController extends Controller
{
    //mereturnkan semua data yang ada pada video
    public function index(Request $request){
        $limit = $request->query('limit') ?? 100;
        $video = VideoGallery::paginate($limit);

        if(count($video) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $video
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada video
    public function show($video_id){
        $video = VideoGallery::where('video_id', $video_id)->first();

        if(!is_null($video)){
            return response([
                'message' => 'Retrieve Video Success',
                'data' => $video
            ], 200);
        }

        return response([
            'message' => 'Video Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada video
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'gallery_videopath' => 'nullable',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $video = VideoGallery::create([
            'gallery_videopath' => $request->gallery_videopath
        ]);

        return response([
            'message' => 'Add Video Success',
            'data' => $video
        ], 200);
    }


    //menghapus data pada video
    public function destroy($video_id){
        $video = VideoGallery::where('video_id', $video_id);

        if(is_null($video)){
            return response([
                'message' => 'Video Not Found',
                'date' => null
            ], 404);
        }

        if($video->delete()){
            return response([
                'message' => 'Delete Video Success',
                'data' => $video
            ], 200);
        }

        return response([
            'message' => 'Delete Video Failed',
            'data' => null,
        ], 400);
    }

    //update data pada video
    public function update(Request $request, $video_id){
        $video = VideoGallery::where('video_id', $video_id)->first();

        if(is_null($video)){
            return response([
                'message' => 'Video Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'gallery_videopath' => 'nullable'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $video->gallery_videopath = $updateData['gallery_videopath'];


        if($video->save()){
            return response([
                'message' => 'Update Video Success',
                'data' => $video
            ], 200);
        }

        return response([
            'message' => 'Update Video Failed',
            'data' => null
        ], 400);
    }
}
