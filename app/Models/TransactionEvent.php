<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransactionEvent extends Model
{
    use HasFactory;
    protected $primaryKey = 'transactionevent_id';
    protected $fillable = [
        'user_id',
        'admin_id',
        'event_id',
        'transactionevent_datebuy',
        'transactionevent_quantity',
        'transactionevent_totalprice',
        'transactionevent_status',
        'transactionevent_proofpayment',
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
    public function Event(){
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
