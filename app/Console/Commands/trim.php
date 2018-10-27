<?php

namespace App\Console\Commands;

use App\Test;
use App\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Trim extends Command
{
  protected $signature = 'trim';
  protected $description = '';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $tests = Test::where('active', true)->get();
    foreach($tests as $test){
      if (Result::where('test_id', $test->id)->count() > 10){
        $maxResult = Result::where('test_id', $test->id)->orderBy('fitness', 'DESC')->first();
        $minResult = Result::where('test_id', $test->id)->orderBy('fitness', 'ASC')->first();

        if ($maxResult !== null){
          Result::where('id', $maxResult->id)->delete();
        }
        if ($minResult !== null){
          Result::where('id', $minResult->id)->delete();
        }
      }
    }
  }
}