<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerPerfs extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_perfs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('measure_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('session_id')->nullable();
            $table->date('date')->nullable();
            $table->decimal('value', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_perfs');
    }
}