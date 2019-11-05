<?php namespace Acte\ClubManager\Models;

use Model;
use Carbon\Carbon;
use Log;
use ApplicationException;

class Ticket extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = false;

    public $table = 'acte_clubmanager_tickets';
    protected $dates = ['start_date', 'expire_date'];

    public $fillable = ['member_id', 'membershiptype_id', 'start_date', 'is_paid', 'paid_on'];

    public $rules = [
      //'member' => 'required', /* is not required anymore due to deferred binding */
      'membershiptype' => 'required',
      'amount' => 'required|numeric',
      'discount' => 'nullable|numeric|max:100',
      'session_count' => 'nullable|numeric',
      'start_date' => 'required|date',
      'expire_date' => 'date|after:start_date',
      'validity_extend' => 'nullable|string',
      'is_paid' => 'bool',
      'paid_on' => 'required_if:is_paid,1|nullable|date|before:tomorrow'
    ];

    public $belongsTo = [
      'member' => 'Acte\ClubManager\Models\Member',
      'membershiptype' => 'Acte\ClubManager\Models\MembershipType',
    ];


    /* SCOPES */

    public function scopeIsPaid($query){
      return $query->where('is_paid', true)->orderBy('paid_on', 'asc');
    }
    public function scopeIsNotPaid($query){
      return $query->where('is_paid', false)->orderBy('paid_on', 'asc');
    }

    public function scopeIsSessionBased($query){
      return $query->where('session_count', ">", 0);
    }

    public function scopeIsTimeBased($query){
      return $query->where('session_count', null);
    }


    public function scopeIsValid($query, $date = null){
      if($date === null){ $date = Carbon::today(); }

      return $query
        ->where('expire_date', '>=', $date)
        ->where('start_date', '<=', $date)
        ->orderBy('expire_date', 'asc');
    }

    /* EVENTS */
    public function beforeValidate(){


      $ticketPrice = $this->membershiptype()->first()->price;

      if($this->discount){
        $this->amount = ( $ticketPrice * (1 - ($this->discount/100)) );
      } else {
        $this->amount = $ticketPrice;
      }

      $this->session_count = $this->membershiptype()->first()->session_count;

      $validity = $this->membershiptype()->first()->validity;
      $this->expire_date = $this->start_date->add($validity);

      if($this->validity_extend){
        $this->expire_date = $this->expire_date->add($this->validity_extend);
      }
    }


    public function beforeDelete(){
      if( Settings::get('lock_archives', true) && $this->is_paid ){
        throw new ApplicationException("Cannot delete a paid ticket!");
      }
    }


}
