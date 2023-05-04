<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Merchandise extends Model
{
    use HasFactory;

    protected $primaryKey = 'merchandise_id';
    protected $fillable = [
        'merchandise_id',
        'merchandisectg_id',
        'merchandise_name',
        'merchandise_picture',
        'merchandise_description',
        'merchandise_nameaccount',
        'merchandise_accountnumber',
        'merchandise_bankname',
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

    public function MerchandiseCategory(){
        return $this->belongsTo(MerchandiseCategory::class, 'merchandisectg_id', 'merchandisectg_id');
    }
}
