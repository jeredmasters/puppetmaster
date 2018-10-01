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
    $lowest_samples = 999999;
    $total_samples = 0;
    $points = 0;
    $parameters = $request->input('parameters');
    
    $results = [];

    $y_col = 'results.'.$parameters['y']['column'];
    foreach($parameters['sets'] as $set){
      $data = [];
      foreach($parameters['x']['values'] as $x){
        $x_col = 'tests.'.$parameters['x']['column'];
        $q = DB::table('results')
          ->join('tests', 'tests.id', '=', 'results.test_id')
          ->where($x_col, '=', $x)
          ->where('fitness', '!=', -1)
          ->where('tests.active', true);
        foreach($parameters['static'] as $key => $value){
          $q->where($key, $value);
        }

        foreach($set['filter'] as $col => $val){
          $q = $q->where('tests.'.$col, $val);
        }        

        

        $y = $q->avg($y_col);
        $samples = $q->count($y_col);
        $total_samples += $samples;
        if ($samples < $lowest_samples){
          $lowest_samples = $samples;
        }


        $data[] = [
          "x" => $x,
          "y" => floatval($y),
          "samples" => $samples,
          "stdDev" => $this->stdDev($q->pluck($y_col)->toArray())
        ];
        $points += 1;
      }
      $results[] = [
        "label" => $set['label'],
        "data" => $data
      ];
    }

    return response()->json(
      [
        'sets' => $results,
        'meta' => [
          'points' => $points,
          'average_samples' => $total_samples / $points,
          'total_samples' => $total_samples,
          'lowest_samples' => $lowest_samples 
        ]
      ]
    );
  }

  private static function stdDev($a){
    $n = count($a);
    if ($n === 0) {        
        return 0;
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
