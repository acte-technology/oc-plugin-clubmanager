<?php namespace Acte\ClubManager\Models;

use Model;


class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'acte_clubmanager_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [];

    public function initSettingsData()
    {
        $this->lock_archives = true;
        $this->validity_options = [
          ['number' => 1, 'time' => 'days'],
          ['number' => 1, 'time' => 'weeks'],
          ['number' => 1, 'time' => 'months'],
          ['number' => 3, 'time' => 'months'],
          ['number' => 6, 'time' => 'months'],
          ['number' => 1, 'time' => 'years']
        ];

    }

}
