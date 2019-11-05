<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerMembers extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_members', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_active')->default(1);
            $table->string('category_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_members');
    }
}
