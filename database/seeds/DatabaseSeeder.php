<?php

use Illuminate\Database\Seeder;
use App\Test;
use App\Analysis;


class DatabaseSeeder extends Seeder
{

  
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('tests')->update(['active' => false]);  
    
    $analyses = Analysis::analyses();

    foreach($analyses as $analysis){
      foreach($analysis['graphs'] as $graph){
        foreach($graph['sets'] as $set){
          $filter = $this->parameters($set['filter'], $analysis['static']);
          foreach($analysis['x']['values'] as $xVal){
            $filter[$analysis['x']['column']] = $xVal;
            Test::findOrCreate($filter, true);
          }
        }
      }
    }
  }

  private function parameters($params, $default = null){
    if ($default === null){
      $default = [
        "population" => 100,
        "generations" => 100,     
        "selection_pressure" => 2,
        "duration" => 15000,
        "crossover_rate" => 6,
        "mutation_rate" => 4,
        "mutation_variance" => 0,
        "duration_variance" => 0,
        "steepest_descent" => 1,
        "obstacle" => 0
      ];
    }
    foreach($params as $k => $v){
      $default[$k] = $v;
    }
    return $default;
  }
}
