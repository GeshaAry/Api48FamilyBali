<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Activityjkt48;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    //mereturnkan semua data yang ada pada activity
    public function index(){
        $activity = Activityjkt48::all();

        if(count($activity) > 0){
            return response([
                'message' => 'Retrieve All Activity Success',
                'data' => $activity
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada activity
    public function show($activity_id){
        $activity = Activityjkt48::where('activity_id', $activity_id)->first();

        if(!is_null($activity)){
            return response([
                'message' => 'Retrieve Activity Success',
                'data' => $activity
            ], 200);
        }

        return response([
            'message' => 'Activity Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada Article
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'activity_date' => 'required',
            'activity_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'activity_title' =>  'required',
            'activty_description' =>  'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(!empty($request->activity_thumbnail)){
            $uploadPictureActivity = $request->activity_thumbnail->store('img_activity', ['disk' => 'public']);
        }
        else{
            $uploadPictureActivity = NULL;
        }
        $activity = Activityjkt48::create([
            'activity_date' => $request->activity_date,
            'article_thumbnail' => $uploadPictureActivity,
            'activity_title' => $request->activity_title,
            'activty_description' => $request->activty_description,
        ]);

        return response([
            'message' => 'Add Activity Success',
            'data' => $activity
        ], 200);
    }


    //menghapus data pada activity
    public function destroy($activity_id){
        $activity = Activityjkt48::where('activity_id', $activity_id);

        if(is_null($activity)){
            return response([
                'message' => 'Activity Not Found',
                'date' => null
            ], 404);
        }

        if($activity->delete()){
            return response([
                'message' => 'Delete Activity Success',
                'data' => $activity
            ], 200);
        }

        return response([
            'message' => 'Delete Activity Failed',
            'data' => null,
        ], 400);
    }

    //update data pada Activity
    public function update(Request $request, $activity_id){
        $activity = Activityjkt48::where('activity_id', $activity_id)->first();

        if(is_null($activity)){
            return response([
                'message' => 'Activity Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'activity_date' => 'required',
            'activity_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
            'activity_title' =>  'required',
            'activty_description' =>  'required'
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $activity->activity_date = $updateData['activity_date'];
        if(isset($request->activity_thumbnail)){
            $uploadPictureActivity = $request->activity_thumbnail->store('img_activity', ['disk' => 'public']);
            $activity->activity_thumbnail = $uploadPictureActivity;
        }
        $activity->activity_title = $updateData['activity_title'];
        $activity->activty_description = $updateData['activty_description'];
    
        if($activity->save()){
            return response([
                'message' => 'Update Activity Success',
                'data' => $activity
            ], 200);
        }

        return response([
            'message' => 'Update Activity Failed',
            'data' => null
        ], 400);
    }
}
