<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScaledFitnessColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('results', function (Blueprint $table) {
        $table->integer('scaled_fitness')->default(0);
        $table->index('status');
        $table->index('millis');
        $table->index('scaled_millis');
      });
      Schema::table('benchmarks', function (Blueprint $table) {
        $table->index('host_id');
      });
      Schema::table('hosts', function (Blueprint $table) {
        $table->index('token');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('results', function (Blueprint $table) {
        $table->dropColumn('scaled_fitness');
        $table->dropIndex('status');
        $table->dropIndex('millis');
        $table->dropIndex('scaled_millis');
      });
      Schema::table('benchmarks', function (Blueprint $table) {
        $table->dropIndex('host_id');
      });
      Schema::table('hosts', function (Blueprint $table) {
        $table->dropIndex('token');
      });
    }
}
