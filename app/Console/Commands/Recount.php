<?php

namespace App\Console\Commands;

use App\User;
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
    }
}