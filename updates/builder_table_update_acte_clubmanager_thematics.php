<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateActeClubmanagerThematics extends Migration
{
    public function up()
    {
        Schema::table('acte_clubmanager_thematics', function($table)
        {
            $table->string('unit', 10)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('acte_clubmanager_thematics', function($table)
        {
            $table->dropColumn('unit');
        });
    }
}
