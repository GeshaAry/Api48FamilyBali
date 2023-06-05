<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransactionMerchandise extends Model
{
    use HasFactory;
    protected $primaryKey = 'merchandisetns_id';
    protected $fillable = [
        'user_id',
        'admin_id',
        'merchandisevar_id',
        'merchandisetns_datebuy',
        'merchandisetns_totalprice',
        'merchandisetns_proofpayment',
        'merchandisetns_status',
        'merchandisetns_quantity',
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

    public function User(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function Admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function MerchandiseVariant(){
        return $this->belongsTo(MerchandiseVariant::class, 'merchandisevar_id', 'merchandisevar_id');
    }

    public function transactionMerchandise()
    {
        return $this->hasOneThrough(
            Merchandise::class,
            MerchandiseVariant::class,
            'merchandise_id', // foreign key on the transact variant table...
            'merchandisevar_id', // foreign key on the transact merchandise table...
            'merchandise_id', // primary key on the merchandise table...
            'merchandisevar_id' // primary key on the marchandise variant table...
        );
       
    }

}
