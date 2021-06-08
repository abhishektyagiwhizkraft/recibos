<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	
    protected $fillable = ['code', 'description', 'qty', 'tax', 'price'];


    public static function getPrice($id)
    {

    	//return self::where('id',$id)->pluck('price')->first();

    }
	public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
