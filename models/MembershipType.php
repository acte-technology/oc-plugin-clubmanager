<?php namespace Acte\ClubManager\Models;

use Model;
use Acte\ClubManager\Models\Settings;

class MembershipType extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_membershiptypes';

    public $rules = [
      'is_active' => 'bool',
      'name' => 'required|string|max:255',
      'ref' => 'required|string|max:255',
      'model' => 'required|string|max:64',
      'session_count' => 'required_if:model,ticket|nullable|numeric',
      'validity' => 'required|string|max:64',
      'price' => 'required|numeric',

    ];


    public $hasMany = [
      'ticket' => 'Acte\ClubManager\Models\Tickets'
    ];


    public function scopeIsActive($query){
      return $query->where('is_active', true);
    }

    public function getValidityOptions($value, $formData){
      $options = Settings::get('validity_options');

      $formated = array();

      foreach ($options as $key => $option) {
        $opt = $option['number']." ".$option['time'];
        $formated[$opt] = $opt;
      }

      return $formated;

    }

}
