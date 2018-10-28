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
      ->count();
    $ratio = 0;

    if ($tests > 0){
      $ratio = ($results / $tests);
    }

    $hosts = DB::table('results')
      ->where('created_at', '>', Carbon::now()->addMinute(-30))
      ->distinct('host_id')
      ->count('host_id');

    $rate = DB::table('results')
      ->where('created_at', '>', Carbon::now()->addMinute(-30))
      ->count();

    return view("index", ['tests' => $tests, 'results' => $results, 'ratio' => $ratio, 'hosts' => $hosts, 'rate' => $rate / 30 ]);
  }

  public function analyses(Request $request){
    $analyses = new Analysis;

    return response()->json(
      [
        'analyses' => $analyses->getAnalysesWithResults()
      ]      
    );
  }

  public function bellcurve(Request $request){
    $test = Test::where('population', 100)
      ->where('generations', 200)
      ->where('mutation_variance', 1)
      ->where('mutation_rate', 5)
      ->where('steepest_descent', $request->input('steepest_descent', 'false') == 'true')
      ->first();

    $maxFitness = 8000; // Result::where('test_id', $test->id)->where('status', 'complete')->max('fitness');
    $results = Result::where('test_id', $test->id)->where('status', 'complete')->orderBy('fitness', 'ASC')->get();
    $bucketCount = $request->input('buckets', 100);
    $resolution = $maxFitness / $bucketCount;

    $buckets = [];
    for($i = 0; $i < $bucketCount; $i++){
      $buckets[$i * $resolution] = 0;
    }

    foreach($results as $result){
      $bucket = intval($result->fitness / $resolution) * $resolution;
      if (!isset($buckets[$bucket])){
        $buckets[$bucket] = 0;
      }
      $buckets[$bucket]++;
    }

    $data = [];

    foreach($buckets as $bucket => $count){
      $data[] = [
        'x1' => $bucket,
        'x2' => intval($bucket + $resolution - 1),
        'y' => $count
      ];
    }

    return response()->json([
      'data' => $data
    ]);
  }
}
