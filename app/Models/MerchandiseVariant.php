<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MerchandiseVariant extends Model
{
    use HasFactory;
    protected $primaryKey = 'merchandisevar_id';
    protected $fillable = [
        'merchandisevar_id',
        'merchandise_id',
        'merchandisevar_size',
        'merchandisevar_price',
        'merchandisevar_stock'
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
