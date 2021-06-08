<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\NotaCredit;
use App\Invoices;
use App\InvoiceFormat;
use App\Client;
use App\Product;
use App\Price;
use DataTables;
use Auth;
use PDF;
use Mail;
use DB;


class NotaCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (! Gate::allows('manage_order') ) {
        
             // session()->flash('permissionerror','You are not authorized to access this page.');
            // return redirect()->route('admin.home');

        // }

        return view('admin.nc.list');
    }

    public function list(Request $request)
    {   

     
        $data = NotaCredit::orderBy('id','DESC')->with(['clients'])->get();

       
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        $clients = Client::all();
        $products = Product::all();
        return view('admin.orders.create',compact('clients','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		// echo "<pre>";
		// print_r($request->all());
		// die;
        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		$generatedData = $this->generateNotaNum();
		// echo "<pre>";
		// print_r($generatedData); 
		// die;
		if($generatedData == 'unavailable'){
			
			session()->flash('error','Número de factura no disponible.');

            return redirect()->back();
		}
        $data = $request->all();

        $data['created_by'] = auth()->user()->id;
        $data['format_id'] = $generatedData['format'];
        $data['number'] = $generatedData['number'];
        $data['note_credit_id'] = $generatedData['generated_id'];

        $nota = NotaCredit::create($data);
        
        foreach($request->products as $product){
	
           $save = DB::table('nota_credit_product')->insert(['nota_credit_id' => $nota->id,'product_id' => $product['id'],'initial_order_qty' => $product['qty']]);
		   
		}

        //$order->products()->attach($request->products);

        session()->flash('message','Nota de Credito Creado.');
		//sleep(5);
		//echo "<script>window.open('nota-de-credito-pdf/".$nota->id."', '_blank').focus()</script>";
        return redirect()->to('admin/nota-de-credito-pdf/'.$nota->id);
    }
	
	
	
	public function generateNotaNum()
    {
       
		return $this->getNextId();
    
    }
	
	public function getNextId()
    {   
	    
		$status = $this->updateStatus();
		if($status == 'empty'){
			return 'unavailable';
		}
		$getLastId = NotaCredit::latest()->first();
		$format = InvoiceFormat::where(['status' => 1, 'document_type' => '1'])->whereDate('start_emission_date', '<=', date("Y-m-d"))
            ->whereDate('end_emission_date', '>=', date("Y-m-d"))->first();
		
		if(!empty($getLastId) && $format->id == $getLastId->format_id){
			$number = $getLastId->number+1;
	        $id = $format->format_number.'-'.($number);
			
		}else{
			$number = $format->start_number;
			$id = $format->format_number.'-'.($number);
		}
		return ['generated_id'=>$id,'format'=>$format->id,'number'=>$number];
			
			
		
	}
	public function updateStatus()
    { 
	    
		
        $this->expireStatus();
        		
		$getLastId = Invoices::latest()->first();
		
	    
		$format = InvoiceFormat::where(['status' => 1, 'document_type' => '1'])->whereDate('start_emission_date', '<=', date("Y-m-d"))
            ->whereDate('end_emission_date', '>=', date("Y-m-d"))->first();
		
		if(!empty($format) && $getLastId->number && $getLastId->number == $format->end_number ){
			
			$format->status = 0;
			$format->save();
			$get = InvoiceFormat::whereNull('status')->where('document_type','1')->whereDate('start_emission_date', '<=', date("Y-m-d"))
            ->whereDate('end_emission_date', '>=', date("Y-m-d"))->first();
			
			if(empty($get)){
				return 'empty';
			}else{
				
				$get->status = 1;
				$get->save();
			}
		}else{
			
			$get = InvoiceFormat::whereNull('status')->where('document_type','1')->whereDate('start_emission_date', '<=', date("Y-m-d"))
                 ->whereDate('end_emission_date', '>=', date("Y-m-d"))->first();
			
			if(empty($get)){
				
				$get1 = InvoiceFormat::where(['status' => 1, 'document_type' => '1'])->whereDate('start_emission_date', '<=', date("Y-m-d"))
                ->whereDate('end_emission_date', '>=', date("Y-m-d"))->first();
				
			   if(empty($get1)){
				   return 'empty';
			   }				   
			}else{
				$get->status = 1;
				$get->save();
			}
			
			
		}
		return 'done';
	}
	
	public function expireStatus(){
		$null = InvoiceFormat::WhereNull('status')->where('document_type','1')->whereDate('start_emission_date', '<', date("Y-m-d"))
            ->whereDate('end_emission_date', '<', date("Y-m-d"))->first();
	    if(empty($null)){
			$null = InvoiceFormat::Where(['status' => 1, 'document_type' => '1'])->whereDate('start_emission_date', '<', date("Y-m-d"))
            ->whereDate('end_emission_date', '<', date("Y-m-d"))->update(['status'=>0]);
		}else{
			$null->status = 0;
			$null->save();
		}
	}
	
	

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        
        return view('admin.orders.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		// echo"<pre>";
		// print_r($order->products);
		// die;
        $clients = Client::all();
        $products = Product::all();
        return view('admin.orders.edit',compact('order','clients','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		// echo"<pre>";
        // print_r($request->products);
		// die;
        //DB::table('order_product')->where('order_id',$order->id)->delete();

        $data = $request->all();
		 // echo"<pre>";
        // print_r($data);
		// die;
        $data['update_by'] = auth()->user()->id;

        $order->update($data);
        
        foreach($request->products as $product){
           
		   if(isset($product['pivot_id'])){
           $save = DB::table('order_product')
		           ->where(['id' => $product['pivot_id']])
				   ->first();
						
		    if($save->qty != $product['qty']){
				if($product['qty'] > $save->qty){
					$pro = Product::where('id',$product['id'])->first();
					if($pro->qty < 1){
						$qtyPro = $pro->order_qty+$save->need;
						if((int)$qtyPro > 0){
							
							$pro->qty = $qtyPro;
							$pro->order_qty = '0';
							$pro->save();
							
						}else{
							$pro->order_qty = $qtyPro;
							$pro->save();
						}
						
						$newQty = $product['qty'] - $save->qty;
						$need = $save->need+$newQty;
					    $pivotQty = $save->qty+$newQty;
					    
						DB::table('order_product')->where(['id' => $product['pivot_id']])->update(['qty'=>$pivotQty,'need'=>$need]);
					   
						$newOrderQty = $pro->order_qty - $pivotQty;
					
						$pro->order_qty = $newOrderQty;
						$pro->save();
					
					}else{
						if($pro->qty >= $product['qty']){
							$need = 0;
							$leftQty = $pro->qty - $product['qty'];
							Product::where('id',$product['id'])->update(['qty' => $leftQty]);
						}else{
							$need = $qty - $pro->qty ;
			 
							$leftQty = 0;
							$orderQty = $pro->qty - $qty;
							$totalQty = $getpro->order_qty+$orderQty;
							Product::where('id',$product['id'])->update(['qty' => $leftQty,'order_qty'=>$totalQty]);
							
							$newQty = $product['qty'] - $save->qty;
							$need = $save->need+$newQty;
							$pivotQty = $save->qty+$newQty;
							
							DB::table('order_product')->where(['id' => $product['pivot_id']])->update(['qty'=>$pivotQty,'need'=>$need]);
						   
							
						}
						
					}
					
				}else{
					
					$pro = Product::where('id',$product['id'])->first();
					if($pro->qty < 1){
						$qtyPro = $pro->order_qty + $product['qty'];
						
					    $pro->order_qty = $qtyPro;
						$pro->save();
				
						$newQty = $save->qty - $product['qty'];
						$need = $save->need - $newQty;
					    $pivotQty = $save->qty-$newQty;
					    
						DB::table('order_product')->where(['id' => $product['pivot_id']])->update(['qty'=>$pivotQty,'need'=>$need]);
					   
						// $newOrderQty = $pro->order_qty + $pivotQty;
					
						// $pro->order_qty = $newOrderQty;
						
						// $pro->save();
					
					}else{
						
						if($pro->qty >= $product['qty']){
							$need = 0;
							$leftQty = $pro->qty - $product['qty'];
							Product::where('id',$product['id'])->update(['qty' => $leftQty]);
						}else{
							$need = $product['qty'] - $pro->qty ;
			 
							$leftQty = 0;
							//$orderQty = $pro->qty - $product['qty'];
							$totalQty = $pro->order_qty - $product['qty'];
							Product::where('id',$product['id'])->update(['qty' => $leftQty,'order_qty'=>$totalQty]);
							
							$newQty = $product['qty'] - $save->qty;
							$need = $save->need+$newQty;
							$pivotQty = $save->qty+$newQty;
							
							DB::table('order_product')->where(['id' => $product['pivot_id']])->update(['qty'=>$pivotQty,'need'=>$need]);
						   
							
						}
						
					}
					
				}
			}
		   }else{
			  $need = $this->updateQty($product['id'],$product['qty']);
		   
              $save = DB::table('order_product')->insert(['order_id' => $order->id,'product_id' => $product['id'],'qty' => $product['qty'],'need' => $need]);
		   }
            
        }

        //$order->products()->attach($request->products);

        session()->flash('message','Pedido Actualizado.');
       
        return redirect()->route('admin.orders.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (! Gate::allows('manage_order') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        
        $order = Order::find($id);
        if($order->status > 0){

            session()->flash('message','No puede eliminar este pedido.');

            return redirect()->back();
        }
        $order->products()->detach();

        $order->delete();

        session()->flash('message','Orden Eliminado.');

        return redirect()->back();
    }

    public function dispatch($id)
    {
        if (! Gate::allows('manage_order') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $order = Order::find($id);
        $role = Auth::user()->roles->first()->name;

       
       
            $order->status = 1;
            $order->save();

            session()->flash('message','Orden despachada.');

            return redirect()->back();

        


    }

    public function deliver($id)
    {
        if (! Gate::allows('manage_order') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $order = Order::find($id);
        $role = Auth::user()->roles->first()->name;

        
       
            $order->status = 2;
            $order->save();

            session()->flash('message','Pedido entregado');

            return redirect()->back();

       


    }

    public function approve($id)
    {

        $role = Auth::user()->roles->first()->name;

        if (! Gate::allows('manage_order') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $order = Order::find($id);
        $order->isApproved = 1;
        $order->save();

        session()->flash('message','Pedido aprobada.');

        return redirect()->back();

     }

    public function disapprove($id)
    {
        $role = Auth::user()->roles->first()->name;

        if (! Gate::allows('manage_order') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $order = Order::find($id);
        $order->isApproved = 2;
        $order->save();

        session()->flash('message','Pedido Rechazado.');

        return redirect()->back();

    }

    public function pdf($id)
    {
        if (! Gate::allows('manage_order')) {

            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
		
		
		
        $nota = NotaCredit::where('id',$id)->with(['clients','vendor','cia'])->first();

		/* if(empty($order->invoice)){
			session()->flash('error','La factura no se ha generado.');
            return redirect()->back();
		} */
		
		// echo"<pre>";
        // print_r($nota->products);
		// die;
        //$pdf = PDF::loadView('admin.orders.pdf',compact('order'));
        $customPaper = array(0,0,600,700);
        $pdf = PDF::loadView('admin.pdf.ndc',compact('nota'))->setPaper($customPaper,'portrait'); 
        //$pdf = PDF::loadView('admin.pdf.ndc',compact('order')); 
        //return $pdf->stream();
        return $pdf->download('ndc-'.$nota->note_credit_id.'.pdf');

    }
    
    public function downloadInvoice($id)
    {
        if (! Gate::allows('manage_order')) {

            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
		
		
		
        $order = Order::where('id',$id)->with('clients')->with('invoice')->first();

		if(empty($order->invoice)){
			session()->flash('error','La factura no se ha generado.');
            return redirect()->back();
		} 
		
		// echo"<pre>";
        // print_r($order->invoice->format);
		// die;
        //$pdf = PDF::loadView('admin.orders.pdf',compact('order'));
        $customPaper = array(0,0,780,1440);
        $pdf = PDF::loadView('admin.orders.invoice_pdf',compact('order'))->setPaper($customPaper,'portrait'); 
        //return $pdf->stream();
        return $pdf->download('invoice-'.$order->invoice->id.'.pdf');

    }
    public function emailpdf(Request $request, $id)
    {
        if (! Gate::allows('manage_order')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $order = Order::where('id',$id)->with('clients')->with('invoice')->first();
		
		if(empty($order->invoice)){
			session()->flash('error','La factura no se ha generado.');
            return redirect()->back();
		}
    
        $pdf = PDF::loadView('admin.orders.pdf',compact('order'));

        $data['email'] = $request->email;
        // $path = public_path()."/pdf/receipt-".$receipt->invoice_id.".pdf";

        // $pdf->save($path);

        Mail::send('emails.pdfmail', $data, function($message)use($data,$pdf,$id) {
            $message->to($data['email'],'Cliente')
            ->subject('RECIBO DE PEDIDO')
            ->attachData($pdf->output(), "order-".$id.".pdf");
        });
        session()->flash('message','Expedido.');

        return redirect('admin/orders');
    }
	
	public function getPrice(Request $request)
    {
        
		$qty = $request->qty;
        $prices = Price::where('product_id',$request->pid)->get();
		// $selected = '';
		// foreach($prices as $price){
			// if($qty >= $price->qty_from && $qty <= $price->qty_to){
				// $selected = $price;
				// break;
			// }
		// }
		
	    // echo"<pre>";
        // print_r($selected);
		// die;
		
		return response()->json(['data' => $prices]);
        

    }
	 
	public function ajaxOrder(Request $request)
    {
        
		
        $data = Order::where('client',$request->client)->where('status',0)->get();
		
		return response()->json(['data' => $data]);
        

    }
    
	
	public function storeInvoice(Request $request, $id)
    {
		
		$order = Order::where('id',$id)->first();
		
        $store = new Invoices;
    	$store->invoice_no = $request->invoice_no;
    	$store->number = $request->number;
    	$store->format_id = $request->format;
    	$store->order_id = $id;
        $store->amount = $request->amount ;
    	$store->due_date = $request->due_date ;
        $store->from = Client::getName($order->client);
        $store->from_id = $order->client ;
    	//$store->what_for = $request->what_for ;
    	//$store->purpose = $request->purpose;
    	$store->created_by = auth()->user()->id;
    	$store->save();
    	
    	$order->total = $request->amount;
    	$order->save();
		
		Invoices::updateQty($id);

        return redirect()->route('admin.orders.index');
    }
	
	public function orderDeleteProduct(Request $request, $id){
		
		if($id){
			
			$order = DB::table('order_product')
		           ->where(['id' => $id])
				   ->first();  
			DB::table('order_product')->where('id',$id)->delete();
			
			session()->flash('message','Product Deleted.');

			return redirect('admin/orders/'.$order->order_id);
		
		}
	
	}
	
	public function updateProductQuantity(Request $request)
    {
        if($request->id){

			$quantity = $request->quantity;
			$update = DB::table('order_product')->where(['id' => $request->id])->update(['qty'=>$quantity]);
		
			return response()->json(['data' => $update]);
        
		}
    }
	
}
