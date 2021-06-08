<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use NumberToWords\NumberToWords;
use App\Product;
use App\Order;
use DB;
class Invoices extends Model
{
    public static function latestId(){
    	

    	if(self::max('id')){
    		return self::max('id')+1;
    	}else{
    		return '10001';
    	}
    }
	public function format()
    {
        return $this->hasOne(InvoiceFormat::class,'id','format_id');
    }

    public static function store($request)
	{
        
    	$store = new self;
    	$store->invoice_no = $request->invoice_no;
    	$store->order_id = $request->order_id;
        $store->amount = $request->amount ;
    	$store->due_date = $request->due_date ;
        $store->from = $request->from ;
        $store->from_id = $request->client_id ;
    	$store->what_for = $request->what_for ;
    	$store->purpose = $request->purpose;
    	$store->created_by = auth()->user()->id;
    	$store->save();
		
		self::updateQty($request->order_id);
		
    }
	
	public static function updateQty($order_id){
		
		$order = Order::where('id',$order_id)->with('products')->first();
		
		foreach($order->products as $product){
		  
		  DB::table('order_product')->where(['order_id'=>$order_id, 'product_id'=>$product->id])->update(['need' => 0]);
		   
		}
	}

    public function receipt()
    {
        return $this->hasOne('App\Receipt');
    }
	public function receipts()
    {
        return $this->hasMany('App\Receipt','invoice_id','invoice_no');
    }
	
	public function order()
    {
        return $this->belongsTo(Order::class);
    }
	
	public function vendor()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public static function numberToWord($num){
    

    $num_arr = explode(".",number_format($num,2,".",",")); 
     
    $decnum = $num_arr[1]; 


       $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }

    $num = (int) $num;
    $words = array();
    $list1 = array('', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once',
         'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve'
    );
    $list2 = array('', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa', 'cien');
    $list3 = array('', 'mil', 'millones', 'billones', 'billones', 'cuatrillones','quintillones','sextillones','septillones',"Octillion", "Nonillion", "decillion", "undecillion", "duodecillion", "tredecillion","Quattuordecillion","Quindecillion", "sexdecillion", "septendecillion", "Octodecillion", "novemdecillion", "vigintillion");
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' cien' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    if($decnum > 0){
    $words[] .= " y ";
    if($decnum < 20){
    if(preg_match("/^[0][0-9]+$/", $decnum) == 1){
		 $decnum = ltrim($decnum, '0'); 
		$words[] .= $list1[$decnum];
	}else{
		$words[] .= $list1[$decnum];
	}
    }elseif($decnum < 100){
    $words[] .= $list2[substr($decnum,0,1)];
    $words[] .= " ".$list1[substr($decnum,1,1)].' centavos';
    }
    }
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return ucfirst(implode(' ', $words));

    }
   
}
