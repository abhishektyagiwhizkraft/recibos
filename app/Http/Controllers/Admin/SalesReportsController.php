<?php

namespace App\Http\Controllers\Admin;

use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use DataTables;
use App\User;
use App\Client;
use App\Product;
use App\Permission;
use App\Invoices;
use App\Receipt;
use App\Order;
use PDF;
use DB;
use Mail;


class SalesReportsController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (! Gate::allows('manage_report') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $clients = Client::all();
        $vendors = User::whereHas(
									'roles', function($q){
										$q->where('name', 'sales person');
										$q->orWhere('name', 'sales man');
										$q->orWhere('name', 'Bodega');
									}
								)->get();
        return view('admin.all-reports.index',compact('clients','vendors'));
    }

    public function generate(Request $request)
    {

	   $report = $request->report;
	   if($report == 'client_report'){
		   
		   $result = $this->ventasxCliente($request->all());
		   $customPaper = array(0,0,600,700);
		   
		   $start = $request->desde;
	       $end = $request->hasta;
		   
		   $pdf = PDF::loadView('admin.all-reports.pdf.ventasxcliente',compact('result','start','end'))->setPaper($customPaper,'portrait');

			//return $pdf->stream();
			return $pdf->download('report-'.$result['name'].'.pdf');
		  
		   
	   }elseif($report == 'per_product_report'){
		   
		   $result = $this->perProduct($request->all());
		   
		    $start = date('d/m/Y',strtotime($request->desde));
		   $end = date('d/m/Y',strtotime($request->hasta));
		   $customPaper = array(0,0,700,900);
		   $pdf = PDF::loadView('admin.all-reports.pdf.report_per_product',compact('result','start','end'))->setPaper($customPaper,'portrait');

			return $pdf->download('report-per-product.pdf');
		   
		   
	   }elseif($report == 'client_days_report'){
		   
		   $result = $this->compraxCliente($request->all());
		   $customPaper = array(0,0,600,700);
		   
		   $pdf = PDF::loadView('admin.all-reports.pdf.no_purchase_days',compact('result'))->setPaper($customPaper,'portrait');

			//return $pdf->stream();
			return $pdf->download('report-no-purchase.pdf');
		   
		   
	   }elseif($report == 'daily_report'){
		   
		   $result = $this->diarioDeVentas($request->all());
		    $start = date('d/m/Y',strtotime($request->desde));
		   $end = date('d/m/Y',strtotime($request->hasta));
		   $customPaper = array(0,0,700,900);
		   $pdf = PDF::loadView('admin.all-reports.pdf.daily_report',compact('result','start','end'))->setPaper($customPaper,'portrait');

			return $pdf->download('report-daily_report.pdf');
		   
		   echo"<pre>";
		   print_r($result);
		   die;
		   
	   }elseif($report == 'factura_report'){
		   
		   $result = $this->ventasxFactura($request->all());
		  
		   $tipo = $request->factura_type;
		   $estado = $request->estado_factura;
		   $start = date('d/m/Y',strtotime($request->desde));
		   $end = date('d/m/Y',strtotime($request->hasta));
		   $customPaper = array(0,0,700,900);
		   $pdf = PDF::loadView('admin.all-reports.pdf.report_factura',compact('result','start','end','tipo','estado'))->setPaper($customPaper,'portrait');

			return $pdf->download('report-facturas.pdf');
		   echo"<pre>";
		   print_r($result);
		   die;
		   
	   }elseif($report == 'vendor_report'){
		   
		   $result = $this->ventasxVendedor($request->all());
		   $customPaper = array(0,0,600,700);
		   
		   $start = $request->desde;
	       $end = $request->hasta;
		 
		   $pdf = PDF::loadView('admin.all-reports.pdf.report_per_salesman',compact('result','start','end'))->setPaper($customPaper,'portrait');

			return $pdf->download('report-sales-per-salesman.pdf');
		   
	   }elseif($report == 'comision_report'){
		   
		   $result = $this->reporteComisiones($request->all());
	
		   $customPaper = array(0,0,600,700);
		   
		   $start = $request->desde;
	       $end = $request->hasta;
		   
		   $pdf = PDF::loadView('admin.all-reports.pdf.report_comision',compact('result','start','end'))->setPaper($customPaper,'portrait');

			return $pdf->download('report-comision.pdf');
		   
	   }
	   

    }
	
	public function perProduct($data1)
    {
		
		$start = date('Y-m-d',strtotime($data1['desde']));
		$end = date('Y-m-d',strtotime($data1['hasta']));
		
		$orders = Order::whereDate('created_at','>=',$start)->whereDate('created_at','<=',$end)->pluck('id')->all();
		
		$products = DB::table('order_product')->whereIn('order_id', $orders)->groupBy('product_id')->get();
		
		
		$data= [];
		$c = 0;
		foreach($products as $productid){
			
		$orderids = DB::table('order_product')->where('product_id', $productid->product_id)->get();
		$product = Product::where('id',$productid->product_id)->first();
		    $d = 0;
			$data2  = [];
         	foreach($orderids as $order_id){
				
				if(in_array($order_id->order_id, $orders)){
					
					$client = Order::where('id',$order_id->order_id)->first();
                    if($client->invoice){					
					$data2[$d]['client_name'] = $client->clients->name;
					$data2[$d]['pro_name'] = $product->description;
					$data2[$d]['code'] = $product->code;
					$data2[$d]['qty'] = $order_id->initial_order_qty;
					$data2[$d]['price'] = $product->price;
					$data2[$d]['fac'] = $client->invoice->number;
					$data2[$d]['date'] = date('d/m/Y',strtotime($client->created_at));
					$d++;
					}
					
				}
				
			}
			$data[$c] = $data2;
					

        $c++;		
		}
		return $data;
		echo"<pre>";
		print_r($data);
		die;
		// foreach($orders as $order){
			
		// }
		
	}
	
	public function ventasxCliente($data)
    {
		
	 
	   if($data['gen_type'] == 'client'){
		   
							
		$client = Client::where('id',$data['selected_client'])->first();					
		$orders = Order::where(['client' => $data['selected_client']])->get();
	
			$ordertotal = 0;
			$credito = 0;
			$contado = 0;
			foreach($orders as $order){
				$invoice = Invoices::where('order_id',$order->id)->first();
				if($invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1){
					 $credito+= Helper::credit($invoice);
					 $contado+= Helper::cash($invoice);
					 $ordertotal+= $invoice->amount;
					}
					
				}
				
			}
			$data = [
			          'code' => $client->code,
			          'name' => $client->name,
			          'total' => $ordertotal,
			          'credito' => $credito,
			          'contado' => $contado,
			        ];
					
			
			
						
	   }else{
		   $data = [];
		   
	   }
	   
	   return $data;
	}
	
	public function compraxCliente($data1)
    {   
	     
		
		$clients = Order::where('create_by',$data1['selected_vendor'])->groupBy('client')->pluck('client')->all();
		$data = [];
		$c = 0;
		foreach($clients as $client){
			$order = Order::where('create_by',$data1['selected_vendor'])->where('client',$client)->latest('created_at')->with(['createBy','invoice'])->first();
			
		    $vendor = ($order->createBy) ? $order->createBy->name : 'N/A';
		    $invoice = ($order->invoice) ? $order->invoice->number : 'N/A';
			$date1 = new \DateTime(date('Y-m-d',strtotime($order->created_at)));
            $date2 = new \DateTime(date('Y-m-d'));
            $days  = $date2->diff($date1)->format('%a');
			$data[$c]['client_id'] = $order->client;
			$data[$c]['client_name'] = $order->clients->name;
			$data[$c]['ult_factura'] = $invoice;
			$data[$c]['date'] = date('d/m/Y',strtotime($order->created_at));
			$data[$c]['vendor'] = $vendor;
			$data[$c]['days'] = $days;
			$c++;
		}
		
		return $data;
		
	}
	
	public function diarioDeVentas($data1)
    {    
	
	    $start = date('Y-m-d',strtotime($data1['desde']));
		$end = date('Y-m-d',strtotime($data1['hasta']));
		$invoices = Invoices::whereDate('created_at','>=',$start)->whereDate('created_at','<=',$end)->orderBy('number','ASC')->groupBy(DB::raw('Date(created_at)'))->get();
		
		$data = [];
		$num_first = '';
		$num_last = '';
		$date = '';
		
		$total_grav  = 0;
		$total_exento = 0;
		$total_sub = 0;
		$total_isv = 0;
		$total_total = 0;
		$c = 0;
		foreach($invoices as $key => $invoice){
			$by_date = Invoices::whereDate('created_at',$invoice->created_at)->orderBy('number')->groupBy('order_id')->get();
			
			$gravable = 0;
			$len = count($by_date);
			foreach($by_date as $index => $getdata){
				if($index == '0'){
					$num_first = $getdata->number;
			    }
				
				$order = Order::where('id',$getdata->order_id)->first();
				if($order){
					$gravable+= $order->amount;
				}
				
				if ($index == $len - 1) {
					$num_last = $getdata->number;	
			    }
			
		    }
		
			
			$subtotal = $gravable - 0.00;
			$isv = User::isv($gravable);
			$total = $isv + $subtotal;
			$data[$c]['date'] = $invoice->created_at;
			$data[$c]['num_first'] = $num_first;
			$data[$c]['num_last'] = $num_last;
			$data[$c]['gravable'] = $gravable;
			$data[$c]['exento'] = 0.00;
			$data[$c]['subtotal'] = $subtotal;
			$data[$c]['isv'] = $isv;
			$data[$c]['total'] = $total;
			$c++;
			
		}
			
		return $data;
	}
	
	public function ventasxFactura($data1)
    {
		
		// echo"<pre>";
		// print_r($data);
		// die;
		$start = date('Y-m-d',strtotime($data1['desde']));
		$end = date('Y-m-d',strtotime($data1['hasta']));
		
		$invoices = Invoices::whereDate('created_at','>=',$start)->whereDate('created_at','<=',$end)->with(['order','vendor'])->get();
		if($data1['factura_type'] == 'contadoycredito'){
			if($data1['estado_factura']== 'pagadas'){
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1 && !empty($invoice->order)){
					  $credito = Helper::credit($invoice) + Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
			}elseif($data1['estado_factura']== 'pendientes'){
				
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted > 0 && !empty($invoice->order)){
					  $credito = Helper::credit($invoice) + Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
				
			}else{
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if(!empty($invoice->order)){
					  $credito = Helper::credit($invoice) + Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			
			 return $data;
			}
		}elseif($data1['factura_type'] == 'solocredito'){
			
			if($data1['estado_factura']== 'pagadas'){
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1 && !empty($invoice->order)){
					  $credito = Helper::credit($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
			}elseif($data1['estado_factura']== 'pendientes'){
				
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted > 0 && !empty($invoice->order)){
					  $credito = Helper::credit($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
				
			}else{
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if(!empty($invoice->order)){
					  $credito = Helper::credit($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'credito';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			  
			 return $data;
			}
			
		}elseif($data1['factura_type'] == 'solocontado'){
			
			if($data1['estado_factura']== 'pagadas'){
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1 && !empty($invoice->order)){
					  $credito = Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'contado';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
			}elseif($data1['estado_factura']== 'pendientes'){
				
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted > 0 && !empty($invoice->order)){
					  $credito = Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'contado';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
				}
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			 
			 return $data;
				
			}else{
				$ids = [];
				$c = 0;
				$credito = 0;
				$data = [];
				foreach($invoices as $invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if(!empty($invoice->order)){
					  $credito = Helper::cash($invoice);
					  if($credito > 0){
					  $ids[$c] = $invoice->id;
					  $data[$c]['date'] = date('d/m/Y',strtotime($invoice->created_at));
					  $data[$c]['num'] = $invoice->number;
					  $data[$c]['client'] = $invoice->from;
					  $data[$c]['tipo'] = 'contado';
					  $data[$c]['total_sin_isv'] = $credito;
					  $data[$c]['total'] = ($credito + User::isv($credito));
					  $data[$c]['saldo'] = ($credito + User::isv($credito));
					  $data[$c]['vendor'] = $invoice->vendor->name;
					  $c++;
					  }
					}
					 
				}
				
			 //$invoices = Invoices::whereIn('id',$ids)->with(['order','vendor'])->get();
			
			 return $data;
			}
			
		}
		
				
				
	
	}
	
	public function ventasxVendedor($data)
    {
		$vendors = User::whereHas('roles', function($q){
										$q->where('name', 'sales person');
										$q->orWhere('name', 'sales man');
										$q->orWhere('name', 'Bodega');
									})->get();
		$data = [];
		$c = 0;
		$credito_total = 0;
		$contado_total = 0;
		$subtotal_total = 0;
			$isv_total = 0;
		$total_total = 0;
		foreach($vendors as $vendor){
			$data[$c]['name'] = $vendor->name;
			$orders = Order::where(['create_by'=>$vendor->id])->get();
		
			$sum = 0;
			$subtotal = 0;
			$isv = 0;
			$ordertotal = 0;
			$credito = 0;
			$contado = 0;
			
			foreach($orders as $order){
				$invoice = Invoices::where('order_id',$order->id)->first();
				if($invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1){
					 $credito+= Helper::credit($invoice);
					 $contado+= Helper::cash($invoice);
					 $comision = User::calComision($invoice->amount,$vendor->id);
					 $sum+= $comision;
					 $subtotal+= $invoice->amount;
					}
					
				}
		
				
			}
			
			$total = ($subtotal + (User::isv($subtotal)));
			$data[$c]['credito'] = Helper::customFormat($credito);
			$data[$c]['contado'] = Helper::customFormat($contado);
			$data[$c]['subtotal'] = Helper::customFormat($subtotal);
			$data[$c]['isv'] = Helper::customFormat(User::isv($subtotal));
			$data[$c]['total'] = Helper::customFormat($total);
			 
			$c++;
			
			$credito_total+= $credito;
			$contado_total+= $contado;
			$subtotal_total+= $subtotal;
			$isv_total+= User::isv($subtotal);
			$total_total+= $total;
		}
		
		$data['credito_total'] = $credito_total;
		$data['contado_total'] = $contado_total;
		$data['subtotal_total'] = $subtotal_total;
		$data['isv_total'] = $isv_total;
		
		$data['total_total'] = $total_total;
		
		return $data;
	}
	
	public function reporteComisiones($data)
    {
		$vendors = User::whereHas('roles', function($q){
										$q->where('name', 'sales person');
										$q->orWhere('name', 'sales man');
										$q->orWhere('name', 'Bodega');
									})->get();
		$data = [];
		$c = 0;
		$credito_total = 0;
		$contado_total = 0;
		$comision_total = 0;
		$total_total = 0;
		foreach($vendors as $vendor){
			$data[$c]['name'] = $vendor->name;
			$orders = Order::where(['create_by'=>$vendor->id])->get();
		
			$sum = 0;
			$ordertotal = 0;
			$credito = 0;
			$contado = 0;
			
			foreach($orders as $order){
				$invoice = Invoices::where('order_id',$order->id)->first();
				if($invoice){
					$invoiceIsCompleted = Helper::paid($invoice);
					if($invoiceIsCompleted < 1){
					 $credito+= Helper::credit($invoice);
					 $contado+= Helper::cash($invoice);
					 $comision = User::calComision($invoice->amount,$vendor->id);
					 $sum+= $comision;
					 $ordertotal+= $invoice->amount;
					}
					
				}
				
			}
			
			
			$data[$c]['credito'] = Helper::customFormat($credito);
			$data[$c]['contado'] = Helper::customFormat($contado);
			$data[$c]['total'] = Helper::customFormat($ordertotal);
			$data[$c]['commision'] = Helper::customFormat(sprintf ("%.2f", $sum ));
			 
			$c++;
			
			$credito_total+= $credito;
			$contado_total+= $contado;
			$comision_total+= $sum;
			$total_total+= $ordertotal;
		}
		
		$data['credito_total'] = $credito_total;
		$data['contado_total'] = $contado_total;
		$data['comision_total'] = $comision_total;
		$data['total_total'] = $total_total;
		
		return $data;
		
		
	}
	
	
	
	


    

  


   


}
