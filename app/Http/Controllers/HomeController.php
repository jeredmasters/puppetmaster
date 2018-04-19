<?php

namespace App\Http\Controllers;

use App\Test;
use App\Result;
use App\Benchmark;
use App\Chromosome;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\ValidateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{    
  public function getRatio(){
    $tests = Test::count();
    $results = Result::count();

    if ($tests > 0){
      return response("$results / $tests = " . ($results / $tests));
    }
    return response("no tests");
  }

  public function chart(){
    $results = [];

    for ($pop_size = 20; $pop_size <= 200; $pop_size += 20) {
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $fitness = DB::table('results')
              ->join('tests', 'tests.id', '=', 'results.test_id')
              ->where('tests.population', '=', $pop_size)
              ->where('tests.generations', '=', $gens)
              ->avg('fitness');
        $results[$pop_size][$gens] = $fitness;
      }
    }

    return response()->json($results);
  }
}
