<?php

namespace App\Http\Controllers;

use App\Test;
use App\Result;
use App\Benchmark;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\ValidateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApiController extends BaseController
{    
  private $validator;
  public function __construct(ValidateToken $validator){
    $this->validator = $validator;
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



    $tests = Test::all();

    $min_count = -1;
    $min_test = null;
    foreach($tests as $test){      
      $count = Result::where('test_id', $test->id)->count();
      if ($count < $min_count || $min_test == null){
        $min_count = $count;
        $min_test = $test;
      }
      if ($count == 0){
        break;
      }
    }   

    if ($min_test != null){
      $result = new Result;
      $result->host_id = $host->id;
      $result->test_id = $min_test->id;
      $result->status = 'pending';
      $result->save();

      return response($min_test->toString());
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
}
