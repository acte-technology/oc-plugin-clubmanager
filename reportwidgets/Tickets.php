<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Ticket as TicketModel;
use Log;

class Tickets extends ReportWidgetBase
{

    public function defineProperties()
    {
        return [
          'status' => [
            'title' => 'Filter by status',
            'type' => 'dropdown',
            'default' => 'pending',
            'options' => [
              'all' => 'All',
              'paid' => 'Paid',
              'pending' => 'Pending payment',
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

    public function render()
    {
      $limit = $this->property('limitRecords', 10);
      $status = $this->property('status', 'pending');

      switch ($status) {
        case 'paid':
          $records = TicketModel::
            with('member')
            ->isPaid()
            ->orderBy('paid_on', 'desc')
            ->limit($limit)
            ->get();

          $this->vars['title'] = "Paid tickets";
          $this->vars['title_color'] = "#95b753";

          break;

        case 'pending':
          $records = TicketModel::
            with('member')
            ->where('is_paid', false)
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();

          $this->vars['title'] = "Pending tickets";
          $this->vars['title_color'] = "#e5a91a";

          break;

        default: /* 'all' */
          $records = TicketModel::
            with('member')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

          $this->vars['title'] = "Latest tickets";
          $this->vars['title_color'] = "";

          break;
      }

      //Log::debug($records);

      $this->vars['records'] = $records;

      return $this->makePartial('widget');

    }
}
