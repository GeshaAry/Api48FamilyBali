<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Event extends Model
{
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'event_name',
        'event_date',
        'event_time',
        'event_location',
        'event_price',
        'event_ammountticket',
        'event_description',
        'event_nameaccount',
        'event_accountnumber',
        'event_bankname',
        'event_verification',
        'event_thumbnail'
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

    public function EventComment(){
        return $this->hasMany(EventComment::class, 'eventcomment_id', 'event_id');
    }
}
