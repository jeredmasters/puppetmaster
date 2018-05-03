<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';


    public function toString(){
      $vars = [
        $this->id,
        $this->population,
        $this->generations,            
        $this->selection_pressure,
        $this->duration,
        $this->crossover_rate,
        $this->mutation_rate,
        $this->mutation_variance,
        $this->duration_variance,
        $this->gradient_decent
      ];

      return implode(",", $vars);
    }


    public static function findOrCreate($t, $activate = false){
      $test = static::where($t)->first();
      if ($test == null){
        $test = new static;
        foreach($t as $key => $value){
          $test->$key = $value;
        }
        $test->save();
      }
      else{
        if (!$test->active && $activate){
          $test->active = true;
          $test->save();
        }
      }
      return $test;
    }
}