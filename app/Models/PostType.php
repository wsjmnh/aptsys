<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    protected $table = 'post_type';
    protected $fillable = ['name'];
    public $timestamps = true;
}