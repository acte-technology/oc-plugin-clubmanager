<?php namespace Acte\ClubManager\Models;

use Model;

/**
 * Model
 */
class Goal extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_goals';

    public $belongsTo = [
      'thematic' => Thematic::class,
      'member' => Member::class
    ];

    public $rules = [
      'deadline_date' => 'required|date',
      'thematic' => 'required',
      'comparaison' => 'required',
    ];
}
