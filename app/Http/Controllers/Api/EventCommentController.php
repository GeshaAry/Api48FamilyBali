<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\EventComment;
use Illuminate\Support\Facades\DB;

class EventCommentController extends Controller
{
    public function getEventComment(){
        $eventcomment = EventComment::all(); 

        if(count($eventcomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $eventcomment
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }
      public function index($event_id){
        $eventcomment = EventComment::with(['User'])->whereEventId($event_id)->get(); 

        if(count($eventcomment) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $eventcomment
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
            'event_id' => 'required',
            'user_id' => 'required',
            'event_comment' => 'required'
        ]); 
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $eventcomment = EventComment::create($storeData);

        return response([
            'message' => 'Add Comment Success',
            'data' => $eventcomment
        ], 200); 
    }

    
    public function destroy($id, $eventcomment_id){
        $eventcomment = EventComment::find($eventcomment_id); 

        if(is_null($eventcomment)){
            return response([
                'message' => 'Comment Not Found',
                'date' => null
            ], 404);
        } 

        if($eventcomment->delete()){
            return response([
                'message' => 'Delete Comment Success',
                'data' => $eventcomment
            ], 200);
        }

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null,
        ], 400);
    }

   
    public function update(Request $request, $id, $eventcomment_id){
        $eventcomment = EventComment::find($eventcomment_id);

        if(is_null($eventcomment)){
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'event_id' => 'required',
            'user_id' => 'required',
            'event_comment' => 'required'
        ]); 

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $eventcomment->event_comment = $updateData['event_comment']; 

        if($eventcomment->save()){
            return response([
                'message' => 'Update Comment Success',
                'data' => $eventcomment
            ], 200);
        } 

        return response([
            'message' => 'Update Comment Failed',
            'data' => null
        ], 400);
    }
}
