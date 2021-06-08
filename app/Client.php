<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['code','name','email','direction','mobile','fax','contact','create_by','avatar'];
	
	public static function getName($id){
		return self::where('id',$id)->pluck('name')->first();
	}
	
	public function checkins(){
		return $this->hasMany('App\CheckIn', 'client_id', 'id')->orderBy('created_at');
	}
	
	public function orders(){
		return $this->hasMany('App\Order', 'client')->selectRaw('sum(total)');
	}
}
