<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Recount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recounts the assignemnts for each test.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      DB::table('results')
        ->where('status', 'pending')
        ->where('created_at', '<', Carbon::now()->addHour(-12))
        ->delete();

      DB::table('tests')
        ->update(['assignments' => DB::raw('(select count(results.id) from results where tests.id = results.test_id)')]);   
      
      DB::table('tests')
        ->update(['unstable' => DB::raw('(select count(results.id) >= 5 AND MAX(results.fitness) = -1 from results where tests.id = results.test_id)')]);   
        
      DB::table('results')
        ->whereRaw('(select count(id) from benchmarks where benchmarks.host_id = results.host_id) > 0')
        ->whereRaw('millis IS NOT NULL')
        ->whereRaw('scaled_millis IS NULL')
        ->update(['scaled_millis' => DB::raw('results.millis / (select avg(millis) from benchmarks where benchmarks.host_id = results.host_id)')]);

      DB::table('results')
        ->whereRaw('scaled_millis IS NOT NULL')
        ->whereRaw('scaled_millis > 0')
        ->whereRaw('scaled_fitness IS NULL')
        ->update(['scaled_fitness' => DB::raw('results.fitness / results.scaled_millis')]);        
    }
}