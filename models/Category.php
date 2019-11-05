<?php namespace Acte\ClubManager\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_categories';

    public $rules = [
      'name' => 'required|string|max:64',
      'description' => 'string|max:1024',
    ];
}
