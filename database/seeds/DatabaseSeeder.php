<?php

use Illuminate\Database\Seeder;
use App\Test;


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

    $this->plotMainCharts();
    $this->plotMutationType();
    $this->gradientDescent();
    $this->selectionPressure();
    $this->durationVariance();
  }

  private function parameters($params){
    $default = [
      "population" => 100,
      "generations" => 100,     
      "selection_pressure" => 2,
      "duration" => 15000,
      "crossover_rate" => 6,
      "mutation_rate" => 4,
      "mutation_variance" => 0,
      "duration_variance" => 0,
      "gradient_decent" => 0
    ];
    foreach($params as $k => $v){
      $default[$k] = $v;
    }
    return $default;
  }


  private function plotMainCharts(){
    for ($pop_size = 20; $pop_size <= 200; $pop_size += 20) {
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $t = [
          "population" => $pop_size,
          "generations" => $gens
        ];
        Test::findOrCreate($this->parameters($t), true);
      }
    }
  }
  
  private function plotMutationType(){
    foreach([0, 1, 2] as $mutation_variance){
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $t = [
          "generations" => $gens,
          "mutation_variance" => $mutation_variance
        ];
        Test::findOrCreate($this->parameters($t), true);
      }                
    }
  }
    
  private function gradientDescent(){
    foreach([0, 1] as $gradient_decent){
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $t = [
          "generations" => $gens,
          "gradient_decent" => $gradient_decent
        ];
        Test::findOrCreate($this->parameters($t), true);
      }
    }
  }
  
  private function selectionPressure(){
    foreach([2, 4] as $selection_pressure){
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $t = [
          "generations" => $gens,     
          "selection_pressure" => $selection_pressure
        ];
        Test::findOrCreate($this->parameters($t), true);
      }
    }
  }
  
  private function durationVariance(){
    foreach([0, 1] as $duration_variance){
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $t = [
          "generations" => $gens,
          "duration_variance" => $duration_variance
        ];
        Test::findOrCreate($this->parameters($t), true);
      }
    }
  }
}
