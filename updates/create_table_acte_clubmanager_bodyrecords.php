<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableActeClubmanagerBodyrecords extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_bodyrecords', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('member_id')->nullable();
            $table->date('date')->nullable();
            $table->decimal('neck', 5, 1)->nullable();
            $table->decimal('biceps_l', 5, 1)->nullable();
            $table->decimal('biceps_r', 5, 1)->nullable();
            $table->decimal('bust', 5, 1)->nullable();
            $table->decimal('waist', 5, 1)->nullable();
            $table->decimal('hips', 5, 1)->nullable();
            $table->decimal('thights_high_l', 5, 1)->nullable();
            $table->decimal('thights_high_r', 5, 1)->nullable();
            $table->decimal('thights_l', 5, 1)->nullable();
            $table->decimal('thights_r', 5, 1)->nullable();
            $table->decimal('knee_l', 5, 1)->nullable();
            $table->decimal('knee_r', 5, 1)->nullable();
            $table->decimal('calf_l', 5, 1)->nullable();
            $table->decimal('calf_r', 5, 1)->nullable();
            $table->decimal('weight', 5, 1)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_bodyrecords');
    }
}