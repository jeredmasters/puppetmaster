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

class HomeController extends BaseController
{    
  public function getRatio(){
    $tests = Test::count();
    $results = Result::count();

    if ($results > 0){
      return response("$tests / $results = " . ($tests / $results));
    }
    return response("none");
  }


}
