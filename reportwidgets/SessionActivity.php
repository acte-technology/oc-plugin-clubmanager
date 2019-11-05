<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Session as SessionModel;
use Log;
use Db;
use Carbon\Carbon;

class SessionActivity extends ReportWidgetBase
{
    public function defineProperties()
    {
        return [
          'title' => [
            'title'             => 'Widget title',
            'default'           => 'Session Activity',
            'type'              => 'string',
            'validationPattern' => '^.+$',
            'validationMessage' => 'The Widget Title is required.'
          ]
        ];
    }

    public function render()
    {
      $sessions = SessionModel::
        isCompleted()
        ->select(
          DB::raw('*'),
          DB::raw('COUNT(*) as session_count'),
          DB::raw('DATE_FORMAT(date, "%b %Y") as monthyear')
        )
        ->groupBy(DB::raw('YEAR(date) ASC, MONTH(date) ASC'))
        ->get();

      $this->vars['records'] = $sessions;


      return $this->makePartial('widget');

    }
}
