<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
   //mereturnkan semua data yang ada pada event
   public function index(Request $request){
    $limit = $request->query('limit') ?? 100;
    $event =  Event::paginate($limit);

    if(count($event) > 0){
        return response([
            'message' => 'Retrieve All Event Success',
            'data' => $event
        ], 200);
    }

    return response([
        'message' => 'Empty',
        'data' => null
    ], 400);
}


//mereturnkan data yang dipilih pada event
public function show($event_id){
    $event = Event::where('event_id', $event_id)->first();

    if(!is_null($event)){
        return response([
            'message' => 'Retrieve Event Success',
            'data' => $event
        ], 200);
    }

    return response([
        'message' => 'Event Not Found',
        'data' => null
    ], 400);
}

//menambah data pada event
public function store(Request $request){
    $storeData = $request->all();
    $validate = Validator::make($storeData, [
        'event_name' => 'required',
        'event_date' =>  'required',
        'event_time' =>  'required',
        'event_location' =>  'required',
        'event_price' =>  'required',
        'event_ammountticket' =>  'required',
        'event_nameaccount' =>  'required',
        'event_accountnumber' =>  'required',
        'event_bankname' =>  'required',
        'event_verification' =>  'required',
        'event_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
       
    ]);

    if($validate->fails()){
        return response(['message' => $validate->errors()], 400); //Return error invalid input
    }

    if(!empty($request->event_thumbnail)){
        $uploadPictureEvent = $request->event_thumbnail->store('img_event', ['disk' => 'public']);
    }
    else{
        $uploadPictureEvent = NULL;
    }
    $event = Event::create([
        'event_name' => $request->event_name,
        'event_date' => $request->event_date,
        'event_time' => $request->event_time,
        'event_location' => $request->event_location,
        'event_price' => $request->event_price,
        'event_ammountticket' => $request->event_ammountticket,
        'event_description' => $request->event_description,
        'event_nameaccount' => $request->event_nameaccount,
        'event_accountnumber' => $request->event_accountnumber,
        'event_bankname' => $request->event_bankname,
        'event_verification' => $request->event_verification,
        'event_thumbnail' => $uploadPictureEvent,
        
    ]);

    return response([
        'message' => 'Add Event Success',
        'data' => $event
    ], 200);
}


//menghapus data pada article
public function destroy($event_id){
    $event = Event::where('event_id', $event_id);

    if(is_null($event)){
        return response([
            'message' => 'Event Not Found',
            'date' => null
        ], 404);
    }

    if($event->delete()){
        return response([
            'message' => 'Delete Event Success',
            'data' => $event
        ], 200);
    }

    return response([
        'message' => 'Delete Event Failed',
        'data' => null,
    ], 400);
}

//update data pada Merchandise
public function update(Request $request, $event_id){
    $event = Event::where('event_id', $event_id)->first();

    if(is_null($event)){
        return response([
            'message' => 'Event Not Found',
            'data' => null
        ], 404);
    }

    $updateData = $request->all();

    $validate = Validator::make($updateData, [
        'event_name' => 'required',
        'event_date' =>  'required',
        'event_time' =>  'required',
        'event_location' =>  'required',
        'event_price' =>  'required',
        'event_ammountticket' =>  'required',
        'event_nameaccount' =>  'required',
        'event_accountnumber' =>  'required',
        'event_bankname' =>  'required',
        'event_verification' =>  'required',
        'event_thumbnail' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image',
    ]);

    
    if($validate->fails()){
        return response(['message' => $validate->errors()], 400);
    }

    $event->event_name = $updateData['event_name'];
    if(isset($request->event_thumbnail)){
        $uploadPictureEvent = $request->event_thumbnail->store('img_event', ['disk' => 'public']);
        $event->event_thumbnail = $uploadPictureEvent;
    }
    $event->event_date = $updateData['event_date'];
    $event->event_time = $updateData['event_time'];
    $event->event_location = $updateData['event_location'];
    $event->event_price = $updateData['event_price'];
    $event->event_ammountticket = $updateData['event_ammountticket'];
    $event->event_description = $updateData['event_description'];
    $event->event_nameaccount = $updateData['event_nameaccount'];
    $event->event_accountnumber = $updateData['event_accountnumber'];
    $event->event_bankname = $updateData['event_bankname'];
    $event->event_verification = $updateData['event_verification'];

    if($event->save()){
        return response([
            'message' => 'Update Event Success',
            'data' => $event
        ], 200);
    }

    return response([
        'message' => 'Update Event Failed',
        'data' => null
    ], 400);
}
}
