<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTableActeClubmanagerTickets extends Migration
{
    public function up()
    {
      Schema::table('acte_clubmanager_tickets', function($table)
      {
        $table->string('validity_extend')->nullable();
        $table->integer('discount')->nullable();
      });
    }

    public function down()
    {
      Schema::table('acte_clubmanager_tickets', function($table)
      {
        $table->dropColumn('validity_extend');
        $table->dropColumn('discount');
      });
    }
}