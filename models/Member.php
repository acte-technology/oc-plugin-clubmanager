<?php namespace Acte\ClubManager\Models;

use Model;
use Acte\ClubManager\Models\Ticket;
use Acte\ClubManager\Models\Session;
use Log;
use Carbon\Carbon;
use ApplicationException;
use Lang;

class Member extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_members';

    public $rules = [
      'is_active' => 'bool',
      'first_name' => 'required|string|max:64',
      'last_name' => 'nullable|string|max:64',
      'category' => 'required',
      'email' => 'nullable|email',
      'phone' => 'string|max:16',
      'birth_date' => 'nullable|date|before:today',
    ];

    public $belongsToMany = [
      'category' => [
        'Acte\ClubManager\Models\Category',
        'table' => 'acte_clubmanager_category_member'
      ],
      'session' => [
        'Acte\ClubManager\Models\Session',
        'table' => 'acte_clubmanager_member_session',
      ],
    ];

    public $hasMany = [
      'ticket' => 'Acte\ClubManager\Models\Ticket',
      'bodyrecords' => 'Acte\ClubManager\Models\BodyRecord',
      'goals' => 'Acte\ClubManager\Models\Goal',
      'performances' => 'Acte\ClubManager\Models\Performance',
    ];

    public function getNameAttribute($value){
      return $this->first_name." ".$this->last_name;
    }

    public function getSessionLeftAttribute($value){
      if($value === null){ return 'unlimited'; }
      return $value;
    }

    /*
    public function getSessionLeftAttribute($value){

      if( $this->ticket()->isValid()->isTimeBased()->count() ){
        return 'unlimited';
      }

      $ticket = $this->ticket()->isValid()->isSessionBased()->get();

      if($ticket->count() == null){
        return 0;
      } else {

        $ticketSessionCount = $ticket->sum('session_count');

        $ticketStartDate = $ticket
          ->sortBy('start_date')
          ->first()
          ->start_date;

        $sessionDone = $this
          ->session()
          ->isCompleted()
          ->where('date', ">=", $ticketStartDate)
          ->count();

        $sessionLeft = $ticketSessionCount - $sessionDone;

        // Log::debug([ 'member' => $this->first_name, 'ticket' => $ticket->toArray(), 'ticketSessionCount' => $ticketSessionCount, 'sessionDone' => $sessionDone ]);

        return $sessionLeft;
      }

    }

    */

    //session_purchased
    public function getSessionPurchasedAttribute($value){
      return $this->ticket()->sum('count');
    }

    //session_paid
    public function getSessionPaidAttribute($value){
      return $this->ticket()->isPaid()->sum('count');
    }

    //all_time_session
    public function getAllTimeSessionAttribute($value){
      return $this->session()->isCompleted()->count();
    }

    //ratio, session done / session paid
    public function getRatioAttribute($value){
      $done = $this->session()->isCompleted()->count();
      $paid = $this->ticket()->isPaid()->sum('count');

      if($paid > 0){
        return ( $done / $paid );
      } else {
        return null;
      }
    }

    /* UPDATE SESSION LEFT */
    public function getSessionLeft(){

      if( $this->ticket()->isValid()->isTimeBased()->count() ){
        return null;
      } else {

        $ticket = $this->ticket()->isValid()->isSessionBased()->get();

        if($ticket->count() == null){
          return 0;
        } else {

          $ticketSessionCount = $ticket->sum('session_count');

          $ticketStartDate = $ticket
            ->sortBy('start_date')
            ->first()
            ->start_date;

          $sessionDone = $this
            ->session()
            ->isCompleted()
            ->where('date', ">=", $ticketStartDate)
            ->count();

          $sessionLeft = $ticketSessionCount - $sessionDone;

          // Log::debug([ 'member' => $this->first_name, 'ticket' => $ticket->toArray(), 'ticketSessionCount' => $ticketSessionCount, 'sessionDone' => $sessionDone ]);

          return $sessionLeft;
        }

      }
    }




    /* SCOPES */
    public function scopeIsActive($query){
      return $query->where('is_active', true);
    }

    public function scopeHasNoSessionLeft($query){
      return $query->where('is_active', true)->where('session_left', 0);
    }

    public function scopeHasValidTicketOn($date = null){

      if($date === null){ $date = Carbon::now(); }

      $ticket = $this->ticket()->isValid($date)->get();

      if($ticket->count() == null){ return false; }
      else {
        return true;
      }

    }


    /* Events */

    public function beforeSave(){
      $this->session_left = $this->getSessionLeft();
    }


    public function beforeDelete(){
      if( $this->session()->count() || $this->ticket()->count() ){
        throw new ApplicationException(Lang::get('acte.clubmanager::lang.backend.delete_member_warning'));
      }
    }

}
