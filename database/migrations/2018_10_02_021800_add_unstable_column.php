<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnstableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('tests', function (Blueprint $table) {
        $table->boolean('unstable')->default(false);
        $table->index('population');
        $table->index('generations');            
        $table->index('selection_pressure');
        $table->index('duration');
        $table->index('crossover_rate');
        $table->index('mutation_rate');
        $table->index('mutation_variance');
        $table->index('duration_variance');
        $table->index('steepest_descent');
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
        $table->dropColumn('unstable');
        $table->dropIndex('population');
        $table->dropIndex('generations');            
        $table->dropIndex('selection_pressure');
        $table->dropIndex('duration');
        $table->dropIndex('crossover_rate');
        $table->dropIndex('mutation_rate');
        $table->dropIndex('mutation_variance');
        $table->dropIndex('duration_variance');
        $table->dropIndex('steepest_descent');
      });
    }
}
