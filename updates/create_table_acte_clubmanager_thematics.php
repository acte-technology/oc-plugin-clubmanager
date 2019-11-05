<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableActeClubmanagerThematics extends Migration
{
    public function up()
    {

        Schema::create('acte_clubmanager_thematics', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();               
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });


        Schema::create('acte_clubmanager_session_thematic', function($table)
        {
            $table->integer('session_id')->unsigned();
            $table->integer('thematic_id')->unsigned();
            $table->primary(['session_id', 'thematic_id']);
        });


    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_thematics');
        Schema::dropIfExists('acte_clubmanager_session_thematic');
    }
}