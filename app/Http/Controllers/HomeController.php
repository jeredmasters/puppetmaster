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
  public function index(){
    $tests = Test::count();
    $results = Result::count();
    $ratio = 0;

    if ($tests > 0){
      $ratio = ($results / $tests);
    }

    return view("index", ['tests' => $tests, 'results' => $results, 'ratio' => $ratio]);
  }

  public function results(){
    $results = [];

    for ($pop_size = 20; $pop_size <= 200; $pop_size += 20) {
      $data = [];
      for ($gens = 20; $gens <= 200; $gens += 20) {
        $fitness = DB::table('results')
              ->join('tests', 'tests.id', '=', 'results.test_id')
              ->where('tests.population', '=', $pop_size)
              ->where('tests.generations', '=', $gens)
              ->avg('fitness');
        $data[] = [
          "x" => $gens,
          "y" => $fitness
        ];
      }
      $results[] = [
        "population" => $pop_size,
        "data" => $data
      ];
    }

    return response()->json($results);
  }
}
