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
        $this->duration_variance
      ];

      return implode(",", $vars);
    }
}