<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\CheckIn;
use App\InvoiceFormat;
use DB;

class NotaCredit extends Model
{

	protected $fillable = ['client_id','invoice_no','vendor_id','amount','tax','total','created_by','description','format_id','number','note_credit_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['id','initial_order_qty']);
    }
    public function clients()
    {
        return $this->hasOne(Client::class,'id','client_id');
    }
	
	public function vendor() {
		
		return $this->hasOne('App\User', 'id', 'vendor_id');
		
    }
	public function cia() {
		
		return $this->hasOne('App\InvoiceFormat', 'id', 'format_id');
		
    }
	
	public function invoice()
    {
        return $this->hasOne(Invoices::class);
    }
	
	public static function priceByQty($pivot_id)
    {
        $pivotData = DB::table('order_product')->where(['id'=>$pivot_id])->first();
		$product = Product::where('id',$pivotData->product_id)->first();
		$getPrice = '';
		foreach($product->prices as $price){
			if($pivotData->qty >= $price->qty_from && $pivotData->qty <=  $price->qty_to){
						
			    $getPrice = $price->price;
				
			}
			if($pivotData->qty >= $price->qty_from && $pivotData->qty >= $price->qty_to){
				
				$getPrice = $price->price;
			}
		}
		return $getPrice;
		
    }
	
	public static function discountTotalByPro($pivot_id)
    {
        $pivotData = DB::table('order_product')->where(['id'=>$pivot_id])->first();
		$product = Product::where('id',$pivotData->product_id)->first();
		$getPrice = '';
		foreach($product->prices as $price){
			if($pivotData->qty >= $price->qty_from && $pivotData->qty <=  $price->qty_to){
						
			    $getPrice = $price->price;
				
			}
			if($pivotData->qty >= $price->qty_from && $pivotData->qty >= $price->qty_to){
				
				$getPrice = $price->price;
			}
		}
	     
		$result = self::discountCalc($getPrice,$pivotData->qty,$product->prices[0]->price);
		return $result;
		
    }
	public static function discountCalc($newPrice,$qty,$normalPrice){
		$normalTotal = $normalPrice*$qty;
		$newTotal = $newPrice*$qty;
		
		$discount = $normalTotal-$newTotal;
		$tax = self::tax($newTotal);
		$totalWithTax = $tax+$newTotal;
		$arr = ['discount'=>$discount,'total'=>$totalWithTax];
		return $arr; 
	
		
	}
	
	
	public static function totalAndTax($id)
    {
        //$pivotData = DB::table('order_product')->where(['id'=>$pivot_id])->first();
		$order = self::where('id',$id)->first();
		$sm = 0;
		$tax = 0;
		$discountbp = 0;
		foreach($order->products as $product){
		$products = Product::where('id',$product->id)->first();
		$getPrice = '';
		
		foreach($products->prices as $price){
			
			if($product->pivot->qty >= $price->qty_from && $product->pivot->qty <=  $price->qty_to){
						
			    $getPrice = $price->price;
				
			}
			if($product->pivot->qty >= $price->qty_from && $product->pivot->qty >= $price->qty_to){
				
				$getPrice = $price->price;
			}
			
			
			
		}
		
		    $discount = self::discountCalc($getPrice,$product->pivot->qty,$products->prices[0]->price);
		
		    $total = $getPrice * $product->pivot->qty;
			  
            $sm+= $total;
            $tax+= self::tax($total);
			$discountbp+= $discount['discount'];
		}
	     
		
		return ['total'=>$sm,'tax'=>$tax,'discount'=>$discountbp];
    }
	public static function tax($amount){
         return ((15/100)*($amount));
    }
    
     public static function uploadImg($data){
         
        
         
        $client = Client::find($data->client);
        
        $imageName = time().'.'.$data->web_img->extension();  
        $checkinExist = CheckIn::where('order_id', $data->order_id)->count();
        if($checkinExist > 0){
            return true;
        }
        if($data->hasFile('web_img')) {
            
                $file = $data->web_img;
                $img = \Image::make($file);
                $fileSize =  $img->filesize();
               
                if($fileSize > 1000000){
                    $img->resize(500, 500);
                }
                $img->text($client->name, $img->width()-40, 100, function($font) {
                    $font->file(public_path('fonts/MONOFONT.TTF'));
                    $font->size(20);
                    $font->color('#fdf6e3');
                    $font->align('right');
                    $font->valign('bottom');
                   
                });
                
                $img->text(date('d-m-Y h:i:s a', time()), $img->width() - 250, 100, function($font) {
                    $font->file(public_path('fonts/MONOFONT.TTF'));
                    $font->size(24);
                    $font->color('#fdf6e3');
                    $font->align('right');
                    $font->valign('top');
                    
                   
                });
                
                $img->save(public_path('uploads/'.$imageName));
                
                $checkin = new CheckIn();
                $checkin->client_id = $data->client;
                $checkin->order_id = $data->order_id;
                $checkin->image = $imageName;
                $checkin->check_in_by = auth()->user()->id;
                $checkin->save();
                
        return true;        
           
        }
        return false;
   
        //$data->web_img->move(public_path('uploads'), $imageName);
         
     
         
     }
    
  
	
	
}
