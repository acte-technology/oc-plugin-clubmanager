<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Ticket as TicketModel;
use Log;
use Db;
use Carbon\Carbon;

class MembershipTypes extends ReportWidgetBase
{

    public function defineProperties()
    {
        return [
          'title' => [
            'title'             => 'Widget title',
            'default'           => 'Membership Types',
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
          ],
        ];
    }

    public function render()
    {

      $records = TicketModel::
        isValid()
        ->select(
          Db::raw('*'),
          Db::raw('COUNT(*) as count')
        )
        ->with('membershiptype')
        ->groupBy('membershiptype_id')
        ->get();

      switch ($this->property('range')) {
        case 'YTD':

          $this->vars['records'] = $records
            ->where( 'start_date', '>=', Carbon::now()->subYear(1) );

          $this->vars['count'] = TicketModel::isValid()
            ->where( 'start_date', '>=', Carbon::now()->subYear(1) )
            ->count();

          break;

        case 'year':
          $this->vars['records'] = $records
            ->where( 'start_date', '>=', Carbon::now()->format('Y-01-01') );

          $this->vars['count'] = TicketModel::
            isValid()
            ->where( 'start_date', '>=', Carbon::now()->format('Y-01-01') )
            ->count();

          break;

        default:
          $this->vars['records'] = $records;

          $this->vars['count'] = TicketModel::isValid()->count();

          break;
      }





      $this->vars['records'] = $records;

      return $this->makePartial('widget');

    }

}
