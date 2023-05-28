<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id',
        'member_id',
        'user_email',
        'user_password',
        'user_name',
        'user_gender',
        'user_birthdate',
        'user_telephone',
        'user_picture',
        'email_verified_at',
        'token',
        'token_reset_password'
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

    public function Member(){
        return $this->belongsTo(Memberjkt48::class, 'member_id', 'member_id');
    }
}
