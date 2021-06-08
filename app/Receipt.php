<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Commision;
use App\Invoices;
use App\Order;
use App\User;
use Helper;
use Mail;
use DB;
class Receipt extends Model
{
    public static function store($request){
        

        $self = self::count();
    	$invoice = Invoices::where('id',session()->get('invoice_id'))->first();
    	$store = new self;
        if(session()->get('payment_mode') == 'Efectivo'){
            if($self == 0){
                $store->id = 10001;
            }
             $store->invoice_id = $invoice->invoice_no;
             $store->payment_mode = session()->get('payment_mode');
             $store->total_payment = $request->total_payment;
             $store->concept = ($request->concept) ? $request->concept : '';
             $store->status = '1';
             $store->generated_by = auth()->user()->id;
             $store->save();
             
             $email = Helper::adminEmail();
             $data['invoice'] = $invoice;
             $data['receipt'] = $store;

            Mail::send('emails.auth_cash', $data, function($message)use($email) {
                $message->to($email,'User')
                ->subject('Need Cash Authorization');
            });


        }else{
            if($self == 0){
            $store->id = 10001;
            }
        	$store->invoice_id = $invoice->invoice_no;
        	$store->payment_mode = session()->get('payment_mode');
        	$store->bank_name = $request->bank_name;
            $store->concept = ($request->concept) ? $request->concept : '';
        	if($request->payment_mode == 'Depositar'){
        		$store->reference_number = $request->reference_number;
        	}elseif($request->payment_mode == 'Cheque'){
                $store->cheque_number = $request->cheque_number;
        	}
            $store->issue_deposit_date = $request->issue_deposit_date;
        	$store->total_payment = $request->total_payment;
            $store->generated_by = auth()->user()->id;
        	$store->save();
        }
		
		$order = Order::find($invoice->order_id);
		
		$invoiceStatus = Helper::paid($invoice);
		
		// Commision to salesman
		if($invoiceStatus < 1){
			
			$comm = User::calComision($order->total,$order->create_by);
			$commision                   = new Commision();
			$commision->user_id          = $order->create_by;
			$commision->order_id         = $order->id;
			$commision->commision_amount = sprintf('%0.2f', $comm);
			$commision->save();
			
			
		}
		
		
		
        session()->forget('invoice_id');
        session()->forget('payment_mode');
		
		

    }
    public function invoice()
    {
        return $this->belongsTo('App\Invoices','invoice_id','invoice_no');  
    }

    public static function getData(){
    
        if(session()->get('method') == 'Efectivo'){
            $data = session()->get('filter-cash');
            if(!empty($data['cash_date'])){
         $records = DB::table('receipts')
             ->select('id','invoice_id','payment_mode','total_payment','created_at')
             ->where(['payment_mode'=>'Efectivo','status'=>'2'])
             ->whereDate('created_at',$data['cash_date'])
             ->get()
             ->toArray();
         }else{
            $records = DB::table('receipts')
             ->select('id','invoice_id','payment_mode','total_payment','created_at')
             ->where('payment_mode','Efectivo')
             ->get()
             ->toArray();
         }
        
        }elseif(session()->get('method') == 'Cheque'){
            $data = session()->get('filter-cheque');
            if(!empty($data['bank']) && empty($data['cheque_no'])){
                $records = DB::table('receipts')
                      ->select('id','invoice_id','payment_mode','bank_name','cheque_number','total_payment','issue_deposit_date')
                      ->where(['payment_mode'=>'Cheque','bank_name'=>$data['bank'],'status'=>'2'])
                      ->get()
                      ->toArray();
            }elseif(empty($data['bank']) && !empty($data['reference_no'])){

                $records = DB::table('receipts')
                      ->select('id','invoice_id','payment_mode','bank_name','cheque_number','total_payment','issue_deposit_date')
                      ->where(['payment_mode'=>'Cheque','cheque_number'=>$data['cheque_no'],'status'=>'2'])
                      ->get()
                      ->toArray();

            }else{
            $records = DB::table('receipts')
                       ->select('id','invoice_id','payment_mode','bank_name','cheque_number','total_payment','issue_deposit_date')
                       ->where(['payment_mode'=>'Cheque'])
                       ->get()
                       ->toArray();
                   }
        }elseif(session()->get('method') == 'Depositar'){

            $data = session()->get('filter-deposit');
            if(!empty($data['bank']) && empty($data['reference_no'])){
                $records = DB::table('receipts')
                      ->select('id','invoice_id','payment_mode','bank_name','reference_number','total_payment','issue_deposit_date')
                      ->where(['payment_mode'=>'Depositar','bank_name'=>$data['bank'],'status'=>'2'])
                      ->get()
                      ->toArray();
            }elseif(empty($data['bank']) && !empty($data['reference_no'])){

                $records = DB::table('receipts')
                      ->select('id','invoice_id','payment_mode','bank_name','reference_number','total_payment','issue_deposit_date')
                      ->where(['payment_mode'=>'Depositar','reference_number'=>$data['reference_no'],'status'=>'2'])
                      ->get()
                      ->toArray();

            }else{
            $records = DB::table('receipts')
                      ->select('id','invoice_id','payment_mode','bank_name','reference_number','total_payment','issue_deposit_date')
                      ->where(['payment_mode'=>'Depositar'])
                      ->get()
                      ->toArray();
                  }
				  
	    }elseif(session()->get('method') == 'salesBy'){
			
               
			   $data = session()->get('filter-salesby');
				  if(!empty($data['by_client'])){
					  $client = $data['by_client'];
						$records = Receipt::select('id','invoice_id','payment_mode','bank_name','reference_number','total_payment','issue_deposit_date')->whereHas('invoice', function($q) use($client) {
							$q->where('from_id',$client);
						})
						//->with('invoice')
						->orderBy('id','DESC')->get();
						
				  }else{
						
						$order_ids = DB::table('order_product')->where('product_id',$data['by_product'])->pluck('order_id')->all();
						
						$invoices_ids = Invoices::whereIn('order_id',$order_ids)->pluck('invoice_no')->all();
						
						$records = self::select('id','invoice_id','payment_mode','bank_name','reference_number','total_payment','issue_deposit_date')->where(['status'=>'2'])
						 ->whereIn('invoice_id',$invoices_ids)
						 ->orderBy('id','DESC')
						 //->with('invoice')
						 ->get();
					}
        }else{
            return back();
        }

        session()->forget('filter-cash');
        session()->forget('filter-deposit');
        session()->forget('filter-cheque');

         return $records;
     
   }
}
