<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{

 protected $table = "check_in";
 
 public static function checkin($orderId){
     
    return self::where('order_id',$orderId)->count();
     
 }
public function checkinby() {				return $this->belongsTo('App\User', 'id', 'check_in_by');		}
 public static function getImage($orderId){
     
    $data = self::where('order_id',$orderId)->first();
    
    return $data->image;
     
 }

}
