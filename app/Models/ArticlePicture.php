<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ArticlePicture extends Model
{
    use HasFactory;

    protected $primaryKey = 'articlepicture_id';
    protected $fillable = [
        'articlepicture_id',
        'article_id',
        'article_picture'
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

    public function ArticlePictures(){
        return $this->hasMany(Article::class, 'article_id', 'article_id'); 
    }
}
