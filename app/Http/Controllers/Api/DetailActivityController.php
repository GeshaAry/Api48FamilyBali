<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailActivity;
use App\Models\Activityjkt48;
use App\Models\Memberjkt48;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetailActivityController extends Controller
{
    //Mereturnkan semua data pada detail Activity
    public function AllDetailActivity(){
        $detailactivity = DetailActivity::with(['Activity', 'Member'])->get();

        if(count($detailactivity) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailactivity
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    

    //mereturnkan data yang dicari pada detail activity
    public function ShowDetailActivity($activity_id){
        // $detailactivity = DetailActivity::with(['Activity', 'Member'])->where('activity_id', $activity_id)->get();

        $detailactivity = Activityjkt48::with(['Member'])->where('activity_id', $activity_id)->first();

        if(!is_null($detailactivity)){
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $detailactivity
            ], 200);
        }

        return response([
            'message' => 'Data Not Found 1',
            'data' => null
        ], 400);
    }

    //menambah data detail activity
    public function store(Request $request){
       
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'activity_id' => 'required',
            'member_id' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $member_id = $request->member_id;
        $data = [];

        foreach ($member_id as $member){
            $data[] = [
                'member_id' => $member,
                'activity_id' => $request->activity_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        
        DB::table('detail_activities')->insert($data);

         
        return response([
            'message' => 'Add Success',
            'data' => $data
        ], 200);

        
         return response([
            'message' => 'Add Activity Failed',
            'data' => null,
        ], 400);
    }
    
    //menghapus data pada detail activity
    public function destroy($detailactivity_id){
        $detailactivity = DetailActivity::where('detailactivity_id', $detailactivity_id);

        if(is_null($detailactivity)){
            return response([
                'message' => 'Data Not Found 2',
                'date' => null
            ], 404);
        }

        if($detailactivity->delete()){
            return response([
                'message' => 'Delete Data Success',
                'data' => $detailactivity
            ], 200);
        }

        return response([
            'message' => 'Delete Data Failed',
            'data' => null,
        ], 400);
    }


    //mengupdate data pada detail activity
    public function update(Request $request, $detailactivity_id){
        $detailactivity = DetailActivity::where('detailactivity_id', $detailactivity_id)->first();

        if(is_null($detailactivity)){
            return response([
                'message' => 'Data Not Found 3',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_id' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $detailactivity->member_id = $updateData['member_id'];

        if($detailactivity->save()){
            return response([
                'message' => 'Update Data Success',
                'data' => $detailactivity
            ], 200);
        }

        return response([
            'message' => 'Update Data Failed',
            'data' => null
        ], 400);
    }
}
