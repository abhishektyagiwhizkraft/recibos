<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Setting;
use App\User;
use App\Client;
use App\Permission;
use App\Invoices;
use App\Receipt;
use App\Order;
use Gerardojbaez\Money\Money;
use Gerardojbaez\Money\Currency;

class Helper
{
	
	public static function customFormat($amount)
    {
		return number_format($amount, 2, '.', ',');
	}
	
    public static function receiptFooter()
    {
        $receipt_address =   Setting::where('setting_key','receipt_address')->pluck('setting_value')->first();
         return unserialize($receipt_address);
    }
    public static function adminEmail()
    {
        return Setting::where('setting_key','admin_email')->pluck('setting_value')->first();

    }
    public static function total_invoices()
    {
       return Invoices::count();
    }

    public static function total_users()
    {
       return User::where('type','!=','1')->count();
    }

    public static function pending_authorization()
    {
       return Receipt::where('status','1')->count();
    }


    public static function paid($invoice)
    {
		
        $total_amount = $invoice->amount;
        $paid = Receipt::where('invoice_id',$invoice->invoice_no)->sum('total_payment');
        $amount = $total_amount-$paid;
        return $total_amount-$paid;

    }		public static function credit($invoice)    {		        $receipts = Receipt::where('payment_mode','!=','Efectivo')->where('invoice_id',$invoice->invoice_no)->get();		        $paid = 0;				if(count($receipts) < 1){						return $paid;					}				foreach($receipts as $receipt){						$paid+= $receipt->total_payment;					}          return $paid;    }		public static function cash($invoice)    {		        $receipts = Receipt::where('payment_mode','Efectivo')->where('invoice_id',$invoice->invoice_no)->get();		        $paid = 0;				if(count($receipts) < 1){						return $paid;					}				foreach($receipts as $receipt){						$paid+= $receipt->total_payment;					}          return $paid;    }		
	
	public static function getInvoiceDate($order_id)
    {
		
       $invoice = Invoices::where('order_id',$order_id)->first();
	   
	   if(!$invoice){
		   return 'N/A';
	   }else{
		    return date("d-m-Y", strtotime($invoice->created_at)); 
	   }

	   
	   
	   
	   
	   

    }

    public static function permissions(){
         
         return Permission::all();

    }
	
	public static function clientTotalInvoices($id)
    {
       
        $total = Invoices::where('from_id',$id)->count();
        
        return $total;

    }
	
	public static function clientTotalOrders($id)
    {
       
        $total = Order::where('client',$id)->count();
        
        return $total;

    }
	
	public static function clientPendingPayment($id)
    {
       
        $invoices = Invoices::where('from_id',$id)->get();
		$sum = 0;
		foreach($invoices as $invoice){
			$total_amount = $invoice->amount;
            $paid = Receipt::where('invoice_id',$invoice->invoice_no)->sum('total_payment');
            $amount = $total_amount-$paid;
			if($amount > 0){
				
			 	$sum+= $amount;
				
			}
			
		}
        
        return $sum;

    }
	
	public static function clientPendingInvoices($id)
    {
       
        $invoices = Invoices::where('from_id',$id)->get();
		$c = 0;
		foreach($invoices as $invoice){
			$total_amount = $invoice->amount;
            $paid = Receipt::where('invoice_id',$invoice->invoice_no)->sum('total_payment');
            $amount = $total_amount-$paid;
			if($amount > 0){
				
			 	$c = $c+1;
				
			}
			
		}
        
        return $c;

    }
	
	public static function clientOrders($id)
    {
       
        $count = Order::where('client',$id)->count();
        return $count;

    }
	
	public static function clientPendingOrders($id)
    {
       
        $count = Order::where('client',$id)->where('status',0)->count();
		
        return $count;

    }
	
	public static function clientProducts($id)
    {
       
        $datas = Order::where('client',$id)->withCount('products')->get();
		$count = 0;
		foreach($datas as $data){
			$count+= $data->products_count;
		}
		// echo"<pre>";
		// echo($count);
		// die;
        return $count;
        

    }
	public static function getClientDetail($id)
    {
       
        $client = Client::where('user_id',$id)->first();
		
        return $client;
        

    }
}