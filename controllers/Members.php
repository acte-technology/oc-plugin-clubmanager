<?php namespace Acte\ClubManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Log;

class Members extends Controller
{
    public $implement = [
      'Backend\Behaviors\ListController',
      'Backend\Behaviors\FormController',
      'Backend\Behaviors\RelationController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'acte.clubmanager.manage_club'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Acte.ClubManager', 'clubmanager', 'members');
    }


}
