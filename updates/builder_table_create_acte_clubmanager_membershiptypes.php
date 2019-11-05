<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerMembershiptypes extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_membershiptypes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_active');
            $table->string('name')->nullable();
            $table->string('ref')->nullable();
            $table->string('model')->nullable();
            $table->integer('session_count')->nullable();
            $table->string('validity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_membershiptypes');
    }
}