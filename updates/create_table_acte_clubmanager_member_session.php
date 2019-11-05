<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableActeClubmanagerMemberSession extends Migration
{
    public function up()
    {
      Schema::create('acte_clubmanager_member_session', function($table)
      {
          $table->integer('session_id')->unsigned();
          $table->integer('member_id')->unsigned();
          $table->primary(['session_id', 'member_id']);
      });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_member_session');
    }
}