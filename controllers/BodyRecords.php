<?php namespace Acte\ClubManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class BodyRecords extends Controller
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

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Acte.ClubManager', 'clubmanager', 'bodyrecords');
    }
}
