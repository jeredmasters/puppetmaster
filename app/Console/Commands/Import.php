<?php

namespace App\Console\Commands;

use App\Test;
use App\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from the main server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
      for($i = 0; $i < 10; $i ++){
        $this->batch();
      }
    }


    public function batch()
    {
      $results = json_decode(file_get_contents('http://api.jered.cc/dump/nice_results'), true);
      
      $test_vals = [
        "population",
        "generations", 
        "selection_pressure",
        "duration",
        "crossover_rate",
        "mutation_rate",
        "mutation_variance",
        "duration_variance",
        "steepest_descent"
      ];

      foreach($results as $r){
        $t = [];
        foreach($test_vals as $k){
          $t[$k] = $r[$k];
        }
        $test = Test::findOrCreate($t);

        Result::findOrCreate([
          'test_id' => $test->id,
          'host_id' => 1,
          'millis' => $r['millis'],
          'fitness' => $r['fitness'],
          'status' => $r['status']
        ]);
      }
    }
}