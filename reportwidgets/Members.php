<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Member as MemberModel;
use Log;

class Members extends ReportWidgetBase
{

    public function defineProperties()
    {
        return [
          // 'title' => [
          //   'title'             => 'Widget title',
          //   'default'           => 'Members',
          //   'type'              => 'string',
          //   'validationPattern' => '^.+$',
          //   'validationMessage' => 'The Widget Title is required.'
          // ],
          'displayMode' => [
            'title' => 'Display mode',
            'type' => 'dropdown',
            'default' => 'topMembers',
            'options' => [
              'topMembers' => 'Top members',
              'invalideMembers' => 'Members without ticket',
            ]
          ],
          'limitRecords' => [
            'title'             => 'Records in the list',
            'description'       => 'Limit number of records in the list',
            'default'           => '10',
            'type'              => 'string',
            'validationPattern' => '^[0-9]+$'
          ]
        ];
    }


    protected $limit;

    public function render()
    {

      $this->limit = $this->property('limitRecords', 10);

      if($this->property('displayMode', 'topMembers') == 'topMembers'){
        return $this->topMembers();
      } else {
        return $this->invalideMembers();
      }

    }


    public function topMembers(){

      $records = MemberModel::
        isActive()
        ->get()
        ->reject(function($item){
        return $item->all_time_session == 0;
        })
        ->take($this->limit)
        ->sortByDesc('all_time_session');

      $this->vars['records'] = $records;

      return $this->makePartial('top_members');

    }

    public function invalideMembers(){

      $records = MemberModel::
        isActive()
        ->get()
        ->reject(function ($record) {
          //reject members with a valid time based membership card
          return $record->ticket()->isTimeBased()->isValid()->first() != null;
        })
        ->reject(function ($record) {
          //reject members with a valid session based ticket and session left
          // warning session_left can return 'unlimited' string when in time based
          return $record->session_left > 0;
        })
        ->take($this->limit);

      $this->vars['records'] = $records;
      return $this->makePartial('invalide_members');


    }
}
