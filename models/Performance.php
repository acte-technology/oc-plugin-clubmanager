<?php namespace Acte\ClubManager\Models;

use Model;

/**
 * Model
 */
class Performance extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;
    public $dates = ['date'];

    public $table = 'acte_clubmanager_perfs';

    public $rules = [
      'value' => 'required|numeric',
      'thematic' => 'required',
      'date' => 'required|date'
    ];

    public $belongsTo = [
      'member' => 'Acte\ClubManager\Models\Member',
      'session' => 'Acte\ClubManager\Models\Session',
      'thematic' => 'Acte\ClubManager\Models\Thematic',
    ];
}
