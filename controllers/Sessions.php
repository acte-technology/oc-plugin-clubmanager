<?php namespace Acte\ClubManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Acte\ClubManager\Models\Session as SessionModel;
use Acte\ClubManager\Models\Member as MemberModel;
use Acte\ClubManager\Models\Ticket as TicketModel;
use Acte\ClubManager\Models\Settings;
use Log;

use Redirect;

use Backend;
use Carbon\Carbon;
use ApplicationException;


class Sessions extends Controller
{
    public $implement = [
      'Backend\Behaviors\ListController',
      'Backend\Behaviors\FormController',
      'Backend.Behaviors.RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';


    public $requiredPermissions = [
        'acte.clubmanager.manage_club'
    ];

    protected $lockArchives;
    protected $ticketFormWidget;


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Acte.ClubManager', 'clubmanager', 'sessions');

        //check if archives modification is enabled
        $this->lockArchives = Settings::get('lock_archives', true);

        //create ticket form widget
        //highly inspired by https://github.com/daftspunk/oc-formist-plugin/blob/master/controllers/Customers.php
        $this->ticketFormWidget = $this->createTicketFormWidget();
    }

    public function update($recordId, $context = null)
    {
      if($this->lockArchives && SessionModel::findOrFail($recordId)->is_completed){
        return Redirect::to(Backend::url("acte/clubmanager/sessions/preview/$recordId"));
      }

      return $this->asExtension('FormController')->update($recordId, $context);
    }



    public function onLoadCreateTicketForm(){

        $this->vars['ticketFormWidget'] = $this->ticketFormWidget;
        $this->vars['memberId'] = post('manage_id');
        return $this->makePartial('ticket_create_form');
    }



    public function onCreateItem() {
        $data = $this->ticketFormWidget->getSaveData();
        $model = new TicketModel;

        $data['membershiptype_id'] = $data['membershiptype'];
        unset($data['membershiptype']);

        $model->fill($data);
        $model->save();

        $member = $this->getMemberModel();
        $member->ticket()->add($model, $this->ticketFormWidget->getSessionKey());

        return $this->refreshMemberTicketList();
    }

    protected function getMemberModel(){

      $memberId = post('manage_id');
      if($memberId){ $member = MemberModel::find($memberId); }
      else{ $member = new MemberModel; }

      return $member;
    }


    protected function createTicketFormWidget(){

      $config = $this->makeConfig('$/acte/clubmanager/models/ticket/fields_relation.yaml');
      $config->alias = 'ticketForm';
      $config->arrayName = 'Ticket';
      $config->model = new TicketModel;
      $widget = $this->makeWidget('Backend\Widgets\Form', $config);
      $widget->bindToController();
      return $widget;
    }


    protected function refreshMemberTicketList()
    {
      $tickets = $this->getMemberModel()
        ->ticket()
        ->withDeferred($this->ticketFormWidget->getSessionKey())
        ->get()
      ;

      $this->vars['tickets'] = $tickets;
      return ['#ticketList' => $this->makePartial('ticket_list')];
    }


}
