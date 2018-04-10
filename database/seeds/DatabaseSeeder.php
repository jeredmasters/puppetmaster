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
    foreach([4, 8] as $mutation_rate){
      foreach([2, 4] as $selection_pressure){
        foreach([0, 1] as $gradient_decent){
          foreach([0, 1] as $duration_variance){
            foreach([0, 1, 2] as $mutation_variance){
              for ($pop_size = 20; $pop_size <= 200; $pop_size += 20) {
                for ($gens = 20; $gens <= 200; $gens += 20) {
                  $test = new Test;
                  $test->population = $pop_size;
                  $test->generations = $gens;     
                  $test->selection_pressure = $selection_pressure;
                  $test->duration = 100 * 120;
                  $test->crossover_rate = 6;
                  $test->mutation_rate = $mutation_rate;
                  $test->mutation_variance = $mutation_variance;
                  $test->duration_variance = $duration_variance;
                  $test->gradient_decent = $gradient_decent;
                  $test->save();
                }
              }
            }
          }
        }
      }
    }
  }
}