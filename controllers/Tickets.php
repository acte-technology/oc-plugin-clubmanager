<?php namespace Acte\ClubManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Backend;
use Redirect;
use Flash;
use Input;
use Carbon\Carbon;
use Log;

use Acte\ClubManager\Models\Ticket as TicketModel;
use Acte\ClubManager\Models\Settings;

class Tickets extends Controller
{
    public $implement = [
      'Backend\Behaviors\ListController',
      'Backend\Behaviors\FormController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'acte.clubmanager.manage_club'
    ];

    protected $lockArchives;

    public function __construct()
    {
      parent::__construct();
      BackendMenu::setContext('Acte.ClubManager', 'clubmanager', 'tickets');

      $widget = new \Acte\ClubManager\ReportWidgets\QuickTicket($this);
      $widget->alias = 'addticketform';
      $widget->bindToController();

      $this->lockArchives = Settings::get('lock_archives', true);
    }


    public function update($recordId, $context = null)
    {
      if($this->lockArchives && TicketModel::findOrFail($recordId)->is_paid){
        return Redirect::to(Backend::url("acte/clubmanager/tickets/preview/$recordId"));
      }

      return $this->asExtension('FormController')->update($recordId, $context);
    }


    public function quickAdd($inputs){

      $ticket = new TicketModel;
      $ticket->member_id = $inputs['member'];
      $ticket->membershiptype_id = $inputs['membershiptype'];
      $ticket->start_date = Carbon::now();
      if(isset($inputs['is-paid'])){
        $ticket->is_paid = true;
        $ticket->paid_on = Carbon::now();
      } else {
        $ticket->is_paid = false;
      }



      if( $ticket->save() ) {
        Flash::success('Ticket added successfully.');
      }
      else {
        Flash::error('Error' );
      }

      return Redirect::to( Backend::url() );

    }

    public function onBulkValidityExtend(){

      $ticketIds = explode(",", post('checked'));
      if(post('number') == 0){
        $validity_extend = null;
      } else{
        $validity_extend = post('number')." ".post('time');
      }

      foreach ($ticketIds as $key => $ticketId) {
        $ticket = TicketModel::find($ticketId);
        $ticket->validity_extend = $validity_extend;
        $ticket->save();
      }

      return $this->listRefresh();

    }

    public function onLoadExtendValidityForm(){

      $this->vars['checked'] = implode(",", post('checked'));
      return $this->makePartial('extend_validity_form');

    }


}
