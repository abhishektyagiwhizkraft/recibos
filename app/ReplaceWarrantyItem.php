<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReplaceWarrantyItem extends Model
{
    protected $fillable = ['item', 's_no', 'fault','qty', 'client_mobile','status','unique_url','received_by'];


    public static function data($id){

    	$items = self::find($id);
    	$data = explode(', ',$items->item);
    	return $data;


    }

    public static function items($order){

    	$items = self::where('order_num',$order)->get();
    	$data = [];
    	foreach($items as $item){

    		$data[] = $item->item;

    	}

    	$return = implode(', ',$data);

    	return $return;


    }
    
    public static function replaced_status($order){

    	$items = self::where(['order_num'=>$order])->count();
    	$replaced = self::where(['order_num'=>$order, 'replaced_status' => 1])->count();
		if($items == $replaced){
			return 'All Items Replaced';
		}else{
			
			return $replaced." items replaced out of ".$items;
		}


    }
	
	public static function if_replaced($order){

    	
    	$replaced = self::where(['order_num'=>$order, 'replaced_status' => 1])->count();
		if($replaced > 0){
			return '1';
		}else{
			
			return '0';
		}


    }
    
    public static function pendingmail($order){
        
      $replaced = self::where(['order_num'=>$order,'sales_confirm'=> 2,'mail_status'=> 0 ,'status'=> '4'])->count();
      
      if($replaced > 0){
          return '1';
      }else{
          return '0';
      }
      
      
        
    }
}
