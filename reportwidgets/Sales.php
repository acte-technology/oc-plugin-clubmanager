<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Ticket as TicketModel;
use Log;
use Db;
use Carbon\Carbon;

class Sales extends ReportWidgetBase
{

    public function defineProperties()
    {
        return [
          'title' => [
            'title'             => 'Widget title',
            'default'           => 'Monthly Sales',
            'type'              => 'string',
            'validationPattern' => '^.+$',
            'validationMessage' => 'The Widget Title is required.'
          ]
        ];
    }


    public function render()
    {
      $tickets = TicketModel::
        isPaid()
        ->select(
          DB::raw('*'),
          DB::raw('COUNT(*) as count'),
          DB::raw('SUM(amount) as total_amount'),
          DB::raw('DATE_FORMAT(paid_on, "%b %Y") as date')
        )
        ->groupBy(DB::raw('YEAR(paid_on) ASC, MONTH(paid_on) ASC'))
        ->get();

      $this->vars['records'] = $tickets;


      return $this->makePartial('widget');

    }
}
