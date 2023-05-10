<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DetailActivity extends Model
{
    use HasFactory;
    protected $primaryKey = 'detailactivity_id';
    protected $fillable = [
        'detailactivity_id',
        'activity_id',
        'member_id',
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
    
    public function Activity(){
        return $this->belongsTo(Activityjkt48::class, 'activity_id', 'activity_id');
    }

    public function Member(){
        return $this->belongsTo(Memberjkt48::class, 'member_id', 'member_id');
    }
}
