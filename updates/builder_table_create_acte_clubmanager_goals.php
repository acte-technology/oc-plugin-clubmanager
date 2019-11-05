<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerGoals extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_goals', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('measure_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->string('comparaison')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline_date')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_goals');
    }
}