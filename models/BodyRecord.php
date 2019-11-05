<?php namespace Acte\ClubManager\Models;

use Model;
use Acte\ClubManager\Models\Member as MemberModel;


class BodyRecord extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_bodyrecords';

    public $rules = [
      'date'            => 'required|date|before:tomorrow',
      'weight'          => 'nullable|numeric',
      'neck'            => 'nullable|numeric',
      'bust'            => 'nullable|numeric',
      'waist'           => 'nullable|numeric',
      'hips'            => 'nullable|numeric',
      'biceps_l'        => 'nullable|numeric',
      'biceps_r'        => 'nullable|numeric',
      'thights_high_l'  => 'nullable|numeric',
      'thights_high_r'  => 'nullable|numeric',
      'thights_l'       => 'nullable|numeric',
      'thights_r'       => 'nullable|numeric',
      'knee_l'          => 'nullable|numeric',
      'knee_r'          => 'nullable|numeric',
      'calf_l'          => 'nullable|numeric',
      'calf_r'          => 'nullable|numeric',
    ];

    public $belongsTo = [
      'member' => [
        'Acte\ClubManager\Models\Member',
      ],
    ];

}
