<?php namespace Acte\ClubManager\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableActeClubmanagerCategoryMember extends Migration
{
    public function up()
    {
      Schema::create('acte_clubmanager_category_member', function($table)
      {
          $table->integer('category_id')->unsigned();
          $table->integer('member_id')->unsigned();
          $table->primary(['category_id', 'member_id']);
      });
    }

    public function down()
    {
        Schema::dropIfExists('acte_clubmanager_category_member');
    }
}