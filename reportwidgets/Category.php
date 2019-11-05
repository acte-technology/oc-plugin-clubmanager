<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Session as SessionModel;
use Log;
use Db;
use Carbon\Carbon;


class Category extends ReportWidgetBase
{

    public function defineProperties()
    {
        return [
          'title' => [
            'title'             => 'Widget title',
            'default'           => 'Category',
            'type'              => 'string',
            'validationPattern' => '^.+$',
            'validationMessage' => 'The Widget Title is required.'
          ],
          'size' => [
            'title'             => 'Chart size',
            'default'           => '150',
            'type'              => 'string',
            'validationPattern' => '^[0-9]+$'
          ],
          'range' => [
            'title'             => 'Range',
            'description'       => 'Date range for report.',
            'type'              => 'dropdown',
            'default'           => 'ytd',
            'options'           => [
              'all'   => 'All',
              'year'  => 'Current Year',
              'ytd'   => 'Year To Date'
            ]
          ]
        ];
    }

    public function render()
    {

      $records = SessionModel::
        isCompleted()
        ->select(
            Db::raw('*'),
            Db::raw('COUNT(*) as count')
        )
        ->with('category')
        ->groupBy('category_id')
        ->get();


      switch ($this->property('range')) {
        case 'YTD':
          $this->vars['records'] = $records
            ->where('date', '>=', Carbon::now()->subYear(1));
          break;

        case 'year':
          $this->vars['records'] = $records
            ->where( 'date', '>=', Carbon::now()->format('Y-01-01') );
          break;

        default:
          $this->vars['records'] = $records;
          break;
      }

      return $this->makePartial('widget');
    }
}
