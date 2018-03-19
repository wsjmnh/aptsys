<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $fillable = ['title', 'content', 'user_id', 'type'];
    public $timestamps = true;


    public function post_type()
    {
        return $this->hasOne('App\Models\PostType', 'id', 'type');
    }

    public function document()
    {
        return $this->hasOne('App\Models\Document', 'post_id', 'id');
    }

}