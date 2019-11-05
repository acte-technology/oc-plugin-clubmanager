<?php namespace Acte\ClubManager\Models;

use Model;
use Flash;
use ValidationException;
use ApplicationException;
use Carbon\Carbon;
use Log;
use Acte\ClubManager\Models\Settings;
use Event;

class Session extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_sessions';
    protected $dates = ['date'];

    public $rules = [
      'category' => 'required',
      'date' => 'required|date',
      'location' => 'string|max:255',
      'comment' => 'string|max:255',
      'is_completed' => 'bool'
    ];

    public $belongsTo = [
      'category' => 'Acte\ClubManager\Models\Category',
    ];

    public $belongsToMany = [
      'member' => [
        'Acte\ClubManager\Models\Member',
        'table' => 'acte_clubmanager_member_session',
      ],
      'member_count' => [
        'Acte\ClubManager\Models\Member',
        'table' => 'acte_clubmanager_member_session',
        'count' => true
      ],
      'thematics' => [
        'Acte\ClubManager\Models\Thematic',
        'table' => 'acte_clubmanager_session_thematic',
      ],
    ];

    /* Accessors / Mutators */
    public function getParticipantsAttribute($value){
      return $this->member()->count();
    }

    /* SCOPES */
    public function scopeIsCompleted($query){
      return $query->where('is_completed', true);
    }


    /* EVENTS */
    public function afterSave(){

      // touch members to update session_left
      $members = $this->member()->withDeferred(post('_session_key'))->get();
      foreach ($members as $key => $member) {
        $member->save();
      }

      $thematics = $this->thematics()->withDeferred(post('_session_key'))->get();

      // fire event if session is completed
      if($this->is_completed){
        Event::fire('acte.clubmanager.session_completed', [$this, $members, $thematics]);
      }

    }



    public function afterValidate(){

      if($this->is_completed){

        $today = Carbon::now();
        if($this->date > $today){
            throw new ValidationException(['date' => "Session cannot be completed, date is after today."]);
            return false;
        }

        foreach ($this->member as $key => $member) {

          //check if member has a valid ticket on session date
          if(!$member->scopeHasValidTicketOn($this->date) || $member->session_left === 0){
            throw new ValidationException(['member' => "Session cannot be completed. $member->name has no valid ticket! ( ".$this->date->format('d-m-Y')." )"]);
            return false;
          }

        }
      }

      return true;

    }

    public function beforeDelete(){
      if( Settings::get('lock_archives', true) && $this->is_completed ){
        throw new ApplicationException("Cannot delete a completed session!");
      }
    }


}
