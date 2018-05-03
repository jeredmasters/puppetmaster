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


    public static function findOrCreate($a){
      $i = static::where($a)->first();
      if ($i == null){
        $i = new static;
        foreach($a as $key => $value){
          $i->$key = $value;
        }
        $i->save();
      }
      return $i;
    }
}