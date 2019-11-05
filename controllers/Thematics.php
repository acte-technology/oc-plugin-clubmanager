<?php namespace Acte\ClubManager\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use System\Classes\SettingsManager;


class Thematics extends Controller
{
    public $implement = [
      'Backend\Behaviors\ListController',
      'Backend\Behaviors\FormController',
      'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'acte.clubmanager.manage_thematics'
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Acte.ClubManager', 'clubmanager', 'thematics');

    }
}
