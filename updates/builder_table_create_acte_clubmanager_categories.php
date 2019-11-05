<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerCategories extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_categories');
    }
}
