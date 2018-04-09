<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'results';
    
    /**
     * Get the comments for the blog post.
     */
    public function host()
    {
        return $this->hasOne('App\Host');
    }
    public function test()
    {
        return $this->hasOne('App\Test');
    }
}