<?php namespace Acte\ClubManager\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Acte\ClubManager\Models\Member;
use Acte\ClubManager\Models\MembershipType;

use Log;
use Input;

class QuickTicket extends ReportWidgetBase
{

    public function defineProperties()
    {
      return [];
    }

    protected $defaultAlias = 'quickticket';

    public function render()
    {
      $this->vars['title'] = "Quick add ticket";

      $this->vars['members'] = Member::isActive()->get();
      $this->vars['membershiptypes'] = MembershipType::isActive()->get();

      return $this->makePartial('widget');
    }

    public function onQuickAdd(){
      $ticketsController = new \Acte\ClubManager\Controllers\Tickets;
      $inputs = Input::get('quick-ticket');
      return $ticketsController->quickAdd($inputs);
    }

}
