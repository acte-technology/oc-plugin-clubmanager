<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerSessions extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_sessions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->string('comment')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_sessions');
    }
}