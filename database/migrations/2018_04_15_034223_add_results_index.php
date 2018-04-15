<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResultsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('results', function (Blueprint $table) {
        $table->index('test_id');
        $table->index('host_id');
      });
      Schema::table('tests', function (Blueprint $table) {
        $table->integer('assignments')->default(0);
      });      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
