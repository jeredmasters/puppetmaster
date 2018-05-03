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
          foreach([100 * 100, 100 * 150, 100 * 200] as $duration){
            foreach([0, 1] as $duration_variance){
              foreach([0, 1, 2] as $mutation_variance){
                for ($pop_size = 20; $pop_size <= 200; $pop_size += 20) {
                  for ($gens = 20; $gens <= 200; $gens += 20) {
                    $t = [
                      "population" => $pop_size,
                      "generations" => $gens,     
                      "selection_pressure" => $selection_pressure,
                      "duration" => $duration,
                      "crossover_rate" => 6,
                      "mutation_rate" => $mutation_rate,
                      "mutation_variance" => $mutation_variance,
                      "duration_variance" => $duration_variance,
                      "gradient_decent" => $gradient_decent
                    ];
                    Test::findOrCreate($t, true);
                  }
                }
              }
            }
          }
        }
      }
    }
  }


}
