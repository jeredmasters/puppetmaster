<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Benchmark extends Model
{
    protected $table = 'benchmarks';
    /**
     * Get the comments for the blog post.
     */
    public function host()
    {
        return $this->hasOne('App\Host');
    }
}