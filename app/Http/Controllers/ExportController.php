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

class ExportController extends BaseController
{    
  public function dump($type){
    switch($type){
      case "tests":
        return response()->json(
          DB::table('tests')
            ->inRandomOrder()
            ->limit(1000)
            ->get()
        );
      case "benchmark":
        return response()->json(
          DB::table('benchmarks')
            ->inRandomOrder()
            ->limit(1000)
            ->get()
        );
      case "hosts":
        return response()->json(
          DB::table('hosts')
            ->inRandomOrder()
            ->limit(1000)
            ->get()
        );
      case "results":
        return response()->json(
          DB::table('results')
            ->inRandomOrder()
            ->limit(1000)
            ->get()
        );
      case "nice_results":
        return response()->json(
          DB::table('results')
            ->join('tests', 'tests.id', '=', 'results.test_id')
            ->inRandomOrder()            
            ->limit(1000)
            ->get()
        );

        
    }
    
  }
}
