<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ArticleComment extends Model
{
    use HasFactory;
    protected $primaryKey = 'articlecomment_id';
    protected $fillable = [
        'articlecomment_id',
        'article_id',
        'user_id',
        'article_comment',
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

    public function Article(){
        return $this->belongsTo(Article::class, 'article_id', 'article_id');
    }

    public function User(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
