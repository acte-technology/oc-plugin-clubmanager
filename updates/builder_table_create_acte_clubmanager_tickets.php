<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateActeClubmanagerTickets extends Migration
{
    public function up()
    {
        Schema::create('acte_clubmanager_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('member_id')->nullable();
            $table->integer('membershiptype_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('session_count')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->date('paid_on')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_tickets');
    }
}