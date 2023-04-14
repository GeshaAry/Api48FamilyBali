<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Activityjkt48 extends Model
{
    use HasFactory;

    protected $primaryKey = 'activity_id';
    protected $fillable = [
        'activity_id',
        'activity_date',
        'activity_thumbnail',
        'activity_title',
        'activty_description'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
