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

class ApiController extends BaseController
{    
  private $validator;
  public function __construct(ValidateToken $validator){
    $this->validator = $validator;
  }

  public function getAssignment_new(){
    $host = $this->validator->getHost();

    $lastBenchmark = Benchmark::where('host_id', $host->id)->orderBy('created_at', 'DESC')->first();
    if ($lastBenchmark == null){
      return response("benchmark");
    }

    $results = Result::where('host_id', $host->id)->where('status', 'complete')->where('updated_at', '>', $lastBenchmark->created_at)->count();
    $daysSince = Carbon::now()->diffInDays($lastBenchmark->created_at);

    if ($results > 50 || $daysSince > 10){
      return response("benchmark");
    }
    
    $test = Test::orderBy('assignments', 'ASC')->first();

    if ($test != null){
      $result = new Result;
      $result->host_id = $host->id;
      $result->test_id = $test->id;
      $result->status = 'pending';
      $result->save();

      $test->assignments = $test->assignments + 1;
      $test->save();

      return response($test->toString());
    }

    return response("none available??");
  }

  public function getAssignment(){
    $host = $this->validator->getHost();

    $lastBenchmark = Benchmark::where('host_id', $host->id)->orderBy('created_at', 'DESC')->first();
    if ($lastBenchmark == null){
      return response("benchmark");
    }

    $results = Result::where('host_id', $host->id)->where('status', 'complete')->where('updated_at', '>', $lastBenchmark->created_at)->count();
    $daysSince = Carbon::now()->diffInDays($lastBenchmark->created_at);

    if ($results > 50 || $daysSince > 10){
      return response("benchmark");
    }
    
    $min_test = DB::table('tests')
      ->leftJoin('results', 'tests.id', '=', 'results.test_id')
      ->select('tests.id', DB::raw('count(results.id)'))
      ->groupBy('tests.id')
      ->orderByRaw('2 asc')
      ->where('tests.active', true)
      ->first();

    if ($min_test != null){
      $test = Test::find($min_test->id);

      $result = new Result;
      $result->host_id = $host->id;
      $result->test_id = $test->id;
      $result->status = 'pending';
      $result->save();

      return response($test->toString());
    }

    return response("none available??");
  }


  public function saveResult(Request $request){
    $test_id = $request->input('test');
    $fitness = $request->input('fitness');
    $millis = $request->input('millis');

    $host = $this->validator->getHost();

    if ($test_id == "benchmark"){
      $benchmark = new Benchmark;
      $benchmark->host_id = $host->id;
      $benchmark->millis = $millis;
      $benchmark->save();
    }
    else{      
      $result = Result::where('test_id', $test_id)
        ->where('host_id', $host->id)
        ->where('status', 'pending')
        ->first();

      if ($result == null){
        return response("test not requested??");
      }

      $result->status = 'complete';
      $result->fitness = $fitness;
      $result->millis = $millis;
      $result->save();
    }

    return response("done");
  }

  
  public function saveChromosome(Request $request){
    $test_id = $request->input('test');
    $fitness = $request->input('fitness');
    $millis = $request->input('millis');
    $chromosome = $request->input('chromosome');

    $host = $this->validator->getHost();

    $c = new Chromosome;
    $c->host_id = $host->id;
    $c->test_id = $test_id;
    $c->fitness = $fitness;
    $c->millis = $millis;
    $c->chromosome = $chromosome;
    $c->save();

    return response("done");
  }

}
