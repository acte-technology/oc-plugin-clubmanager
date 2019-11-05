<?php namespace Acte\ClubManager\Models;

use Model;
use Log;

/**
 * Model
 */
class Thematic extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\NestedTree;

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public $table = 'acte_clubmanager_thematics';

    public $rules = [
      'name' => 'required|string|max:64',
      'description' => 'nullable|string|max:255',
      'unit' => 'nullable|string|max:255',
    ];


    // use \Acte\ClubManager\Traits\Measurable;
    //
    // public $morphOne = [
    //   'measure' => ['Acte\ClubManager\Models\Measure', 'name' => 'measurable']
    // ];
    //
    // public $measurableRecords = [
    //   'select' => 'name',
    //   'unit' => 'unit',
    //   'scope' => 'childsOnly'
    // ];

    public function getParentIdOptions(){
      $parents = $this->get();

      $options = array();
      $options[0] = '-- no parent --';

      foreach ($parents as $key => $parent) {
        $options[$parent->id] = $parent->name;
      }
      return $options;

    }

    public function scopeChildsOnly($query){
      return $query->where('parent_id', '!=', 0);
    }

}
