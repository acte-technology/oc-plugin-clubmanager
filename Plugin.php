<?php namespace Acte\ClubManager;

use System\Classes\PluginBase;
use Event;
use Log;
use BackendAuth;
use Mail;

class Plugin extends PluginBase
{
  public function registerComponents()
  {
  }

  public function registerSettings()
  {
    return [
      'settings' => [
        'label'       => 'acte.clubmanager::lang.backend.settings.title',
        'description' => 'acte.clubmanager::lang.plugin.description',
        'icon'        => 'icon-users',
        'category'    => 'Club Manager',
        'class'       => 'Acte\ClubManager\Models\Settings',
        'order'       => 500,
        'keywords'    => 'club manager ticket session member',
        'permissions' => ['acte.clubmanager.manage_settings']
    ]
    ];
  }

  public function registerReportWidgets()
  {
    return [
      'Acte\ClubManager\ReportWidgets\Sales' => [
        'label'   => 'Sales',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\SessionActivity' => [
        'label'   => 'Session activity',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\Category' => [
        'label'   => 'Category',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\Members' => [
        'label'   => 'Members',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\Tickets' => [
        'label'   => 'Tickets',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\QuickTicket' => [
        'label'   => 'Quick Ticket',
        'context' => 'dashboard'
      ],
      'Acte\ClubManager\ReportWidgets\MembershipTypes' => [
        'label'   => 'MembershipTypes',
        'context' => 'dashboard'
      ],
    ];
  }

  public function registerMailTemplates()
  {
      return [
          'acte.clubmanager::mail.sessionreport',
          'acte.clubmanager::mail.report',
      ];
  }


  public function boot(){

    Event::listen('acte.clubmanager.session_completed', function($session, $members, $thematics){

      Log::debug('session_completed event fired');

      if( count($members) ){

        $params = [
          'session' => $session,
          'members' => $members,
          'thematics' => $thematics,
        ];

        if( BackendAuth::check() ){
          $user = BackendAuth::getUser();
          Mail::sendTo($user->email, 'acte.clubmanager::mail.sessionreport', $params);
        }
      }


    });



  }

}
