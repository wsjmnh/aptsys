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
        return $this->hasOne(PostType::class, 'id', 'type');
    }

    public function document()
    {
        return $this->hasOne('App\Models\Document', 'post_id', 'id');
    }

    public function all_comments()
    {
        return $this->hasMany('App\Models\Comment', 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'post_id', 'id');
    }

    public function getPostId()
    {
        return $this->id;
    }

}