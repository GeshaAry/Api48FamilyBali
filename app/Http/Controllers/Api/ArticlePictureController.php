<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\ArticlePicture;
use Illuminate\Support\Facades\DB;

class ArticlePictureController extends Controller
{
     //mereturnkan semua data yang ada pada member
     public function index(){
        $articlepictures = ArticlePicture::all();

        if(count($articlepictures) > 0){
            return response([
                'message' => 'Retrieve All Article Picture Success',
                'data' => $articlepictures
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
