<?php

namespace App\Http\Controllers;

use App\Test;
use App\Result;
use App\Benchmark;
use App\Chromosome;
use App\Analysis;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\ValidateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{    
  public function index(){
    $tests = Test::where('active', true)->count();
    $results = DB::table('results')
      ->join('tests', 'tests.id', '=', 'results.test_id')
      ->where('tests.active', true)
      ->inRandomOrder()            
      ->limit(1000)
      ->count();
    $ratio = 0;

    if ($tests > 0){
      $ratio = ($results / $tests);
    }

    return view("index", ['tests' => $tests, 'results' => $results, 'ratio' => $ratio]);
  }

  public function analyses(Request $request){
    $analyses = new Analysis;

    return response()->json(
      [
        'analyses' => $analyses->getAnalysesWithResults()
      ]      
    );
  }

}
