<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MerchandiseComment extends Model
{
    use HasFactory;
    protected $primaryKey = 'merchandisecmt_id';
    protected $fillable = [
        'merchandisecmt_id',
        'merchandise_id',
        'user_id',
        'merchandise_comment',
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

    public function Merchandise(){
        return $this->belongsTo(Merchandise::class, 'merchandise_id', 'merchandise_id');
    }

    public function User(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
