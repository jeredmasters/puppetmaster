<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('tests', function (Blueprint $table) {
        $table->boolean('active')->default(true);
      });
      Schema::table('results', function (Blueprint $table) {
        $table->float('scaled_millis')->default(0)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('tests', function (Blueprint $table) {
        $table->dropColumn('active');
      });
      Schema::table('results', function (Blueprint $table) {
        $table->dropColumn('scaled_millis');
      });
    }
}
