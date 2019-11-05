<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateActeClubmanagerPerfs extends Migration
{
    public function up()
    {
        Schema::table('acte_clubmanager_perfs', function($table)
        {
            $table->renameColumn('measure_id', 'thematic_id');
        });
    }
    
    public function down()
    {
        Schema::table('acte_clubmanager_perfs', function($table)
        {
            $table->renameColumn('thematic_id', 'measure_id');
        });
    }
}
