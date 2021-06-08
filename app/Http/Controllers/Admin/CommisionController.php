<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Invoices;
use App\Order;
use App\User;
use Datatables;
use Helper;

class CommisionController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculateCommision(Request $request)
    {
        if($request->ajax()){
			
			
			$orders = Order::where(['create_by'=>$request->user_id])->whereYear('created_at',$request->year)->get();
		
			$sum = 0;
			
			foreach($orders as $order){
				
				$invoice = Invoices::where('order_id',$order->id)->whereMonth('created_at',$request->month)->first();
				if($invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1){
					 //$comision = self::calComision($order->total,$id);
					 $comision = User::calComision($invoice->amount,$request->user_id);
					 $sum+= $comision;
					}else{
					  $sum+= 0;	
					}
				}
			}
			$com   = sprintf ("%.2f",((($sum*100))/100));
			
			$m     = $request->month;
			
		    $month = date( 'F', strtotime( "$m/12/10" ));
			
			return response()->json(['com' => $com, 'month' => $month]);
			
			
			}
        
    }

   

}
