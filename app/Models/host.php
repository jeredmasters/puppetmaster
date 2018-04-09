<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    protected $table = 'hosts';
    /**
     * Get the comments for the blog post.
     */
    public function benckmarks()
    {
        return $this->hasMany('App\Benchmark');
    }
    public function results()
    {
        return $this->hasMany('App\Result');
    }
}