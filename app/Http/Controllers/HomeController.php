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

  public function results(Request $request){
    $parameters = $request->input('parameters');
    
    $results = [];

    $y_col = 'results.'.$parameters['y']['column'];
    foreach($parameters['sets'] as $set){
      $data = [];
      $error = [];
      foreach($parameters['x']['values'] as $x){
        $x_col = 'tests.'.$parameters['x']['column'];
        $q = DB::table('results')
          ->join('tests', 'tests.id', '=', 'results.test_id')
          ->where($x_col, '=', $x)
          ->where('tests.active', true);
        foreach($parameters['static'] as $key => $value){
          $q->where($key, $value);
        }

        foreach($set['filter'] as $col => $val){
          $q = $q->where('tests.'.$col, $val);
        }        

        $y = $q->avg($y_col);

        $data[] = [
          "x" => $x,
          "y" => floatval($y)
        ];
        $error[] = $this->stdDev($q->pluck($y_col)->toArray());
      }
      $results[] = [
        "label" => $set['label'],
        "data" => $data,
        "error" => $error
      ];
    }

    return response()->json($results);
  }

  private static function stdDev($a){
    $n = count($a);
    if ($n === 0) {
        trigger_error("The array has zero elements", E_USER_WARNING);
        return false;
    }
    $mean = array_sum($a) / $n;
    $carry = 0.0;
    foreach ($a as $val) {
        $d = ((double) $val) - $mean;
        $carry += $d * $d;
    };
    return sqrt($carry / $n);
  }
}
