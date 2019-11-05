<?php namespace Acte\ClubManager\Updates;

/* 2019-05-05 */

use Schema;
use October\Rain\Database\Updates\Migration;
use Acte\ClubManager\Models\Member;

class UpdateTableActeClubmanagerMembers extends Migration
{
    public function up()
    {
      Schema::table('acte_clubmanager_members', function($table)
      {
        $table->integer('session_left')->nullable();
      });

      // initialize session_left for existing members
      $members = Member::isActive()->get();
      foreach ($members as $key => $member) {
        $member->save();
      }


    }

    public function down()
    {
      Schema::table('acte_clubmanager_members', function($table)
      {
        $table->dropColumn('session_left');
      });
    }
}