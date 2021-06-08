<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Client;
use App\Product;
use App\Permission;
use App\Invoices;
use App\Receipt;
use Helper;
//use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReceiptsRequest;
use App\Http\Requests\Admin\UpdateReceiptsRequest;
use DataTables;
use PDF;
use DB;
use Mail;


class ReportsController extends Controller
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


        
        $receipts = Receipt::orderBy('id','DESC')->get();
        
        $clients = Client::all();
        $products = Product::all();
        return view('admin.report.index', compact('receipts','clients','products'));
    }

    public function listAll(Request $request)
    {

       if(!empty($request->start_date) && !empty($request->end_date) && empty($request->payment_mode)){
        
            $data = Receipt::whereBetween('created_at', array($request->start_date, $request->end_date))->where('status','2')->orderBy('id','DESC')->with('invoice')->get();
       }elseif(!empty($request->start_date) && !empty($request->end_date) && !empty($request->payment_mode)){
        
         $data = Receipt::whereBetween('created_at', array($request->start_date, $request->end_date))
             ->where('payment_mode',$request->payment_mode)
             ->where('status','2')
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
       }elseif(empty($request->start_date) && empty($request->end_date) && !empty($request->payment_mode) && empty($request->bank) && empty($request->reference) && empty($request->cheque) && empty($request->cash_date)){
        $data = Receipt::where('payment_mode',$request->payment_mode)
             ->where('status','2')
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
        }elseif(!empty($request->bank) && !empty($request->payment_mode) && empty($request->cheque) && empty($request->reference)){
            
           $data = Receipt::where('payment_mode',$request->payment_mode)
             ->where('status','2')
             ->where('bank_name',$request->bank)
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
        }elseif(!empty($request->bank) && !empty($request->payment_mode) && !empty($request->cheque)){
            
            $data = Receipt::where(['status'=>'2','payment_mode'=>$request->payment_mode,'bank_name'=>$request->bank,'cheque_number'=>$request->cheque])
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
         }elseif(!empty($request->bank) && !empty($request->payment_mode) && !empty($request->reference)){
        
        $data = Receipt::where(['status'=>'2','payment_mode'=>$request->payment_mode,'bank_name'=>$request->bank,'reference_number'=>$request->reference])
         ->orderBy('id','DESC')
         ->with('invoice')
         ->get();
     }elseif(!empty($request->cash_date) && !empty($request->payment_mode) && empty($request->bank) && empty($request->reference)){
        
        $data = Receipt::where(['status'=>'2','payment_mode'=>$request->payment_mode])
         ->whereDate('created_at',$request->cash_date)
         ->orderBy('id','DESC')
         ->with('invoice')
         ->get();
     }elseif(!empty($request->payment_mode) && !empty($request->reference)){
            
            $data = Receipt::where(['status'=>'2','payment_mode'=>$request->payment_mode,'reference_number'=>$request->reference])
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
      }elseif(!empty($request->payment_mode) && !empty($request->cheque)){
            
            $data = Receipt::where(['status'=>'2','payment_mode'=>$request->payment_mode,'cheque_number'=>$request->cheque])
             ->orderBy('id','DESC')
             ->with('invoice')
             ->get();
      }elseif(!empty($request->sales_by) && (!empty($request->by_product) || !empty($request->by_client) )){
		  
		   //Receipt::orderBy('id','DESC')->where('status','2')->with('invoice')->get();
		  $client = $request->by_client;
		  if($request->by_client){
				$data = Receipt::whereHas('invoice', function($q) use($client) {
					$q->where('from_id',$client);
				})->with('invoice')->orderBy('id','DESC')->get();
				
		  }else{
			    
				$order_ids = DB::table('order_product')->where('product_id',$request->by_product)->pluck('order_id')->all();
				
				$invoices_ids = Invoices::whereIn('order_id',$order_ids)->pluck('invoice_no')->all();
				
				$data = Receipt::where(['status'=>'2'])
				 ->whereIn('invoice_id',$invoices_ids)
				 ->orderBy('id','DESC')
				 ->with('invoice')
				 ->get();
				 // echo "<pre>";
				 // print_r($data);
				// die;
				
			  
		  }
		  
      }else{

            $data = Receipt::orderBy('id','DESC')->where('status','2')->with('invoice')->get();

      }
       // echo "<pre>";
       // print_r($data);
       // die;
       return DataTables::of($data)->addIndexColumn()->make(true);

    }

    

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('manage_report')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        if(!empty(session()->get('invoice_id'))){

        $invoice = Invoices::find(session()->get('invoice_id'));    

        $check = Receipt::where('invoice_id',$invoice->invoice_no)->first();
        if(! empty($check)){
             session()->flash('error','Receipt already generated for Invoice No. #'.$invoice->invoice_no);
             return redirect()->route('admin.invoices.index');
        }

        if(session()->get('payment_mode') == 'cash'){

            $this->mail_for_cash_auth();
            
            session()->flash('warning','Authorization is required from admin for this transaction.');

            return redirect()->route('admin.receipts.index');

        }

        
            $invoice = Invoices::where('id',session()->get('invoice_id'))->first();
            return view('admin.receipt.create',compact('invoice'));
        }

        session()->flash('error','Please Select Mode of Payment.');
        return redirect()->route('admin.invoices.index');
    } 

    function mail_for_cash_auth(){
         $invoice = Invoices::find(session()->get('invoice_id'));
         $store = new Receipt;
         $store->invoice_id = $invoice->invoice_no;
         $store->payment_mode = session()->get('payment_mode');
         $store->status = '1';
         $store->save();
         
         $email = Helper::adminEmail();
         $data['invoice'] = $invoice;
         $data['receipt'] = $store;

        Mail::send('emails.auth_cash', $data, function($message)use($email) {
            $message->to($email,'User')
            ->subject('Need Cash Authorization');
        });

         return $store;

    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceiptsRequest $request)
    {
        if (! Gate::allows('manage_report')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
       $check = Receipt::where('invoice_id',session()->get('invoice_id'))->first();
       if(! empty($check)){
         session()->flash('error','Receipt already generated for Invoice No. #'.session()->get("invoice_id"));
         return redirect()->route('admin.invoices.index');
       }
       $store = Receipt::store($request);

        return redirect()->route('admin.receipts.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoices $invoice)
    {
        if (! Gate::allows('hasPermission','manage_report')) {
            return abort(401);
        }

        return view('admin.invoices.edit', compact('invoice'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoicesRequest $request, Invoices $invoice)
    {
        if (! Gate::allows('hasPermission','manage_report')) {
            return abort(401);
        }

        $invoice->qty = ($request->qty) ? $request->qty : null ;
        $invoice->amount = $request->amount ;
        $invoice->due_date = $request->due_date ;
        $invoice->tax = $request->tax ;
        $invoice->from = $request->from ;
        $invoice->purpose = $request->purpose;
        $invoice->address = $request->address;
        if($request->tax == 'no'){
            $invoice->tax_percentage = $request->tax_percentage;
        }
        $invoice->save();


        return redirect()->route('admin.invoices.index');
    }

    public function show(Receipt $receipt)
    {
        if (! Gate::allows('manage_report')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $receipt->with('invoice');
        return view('admin.receipt.show', compact('receipt'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        if (! Gate::allows('hasPermission','manage_report')) {

             session()->flash('permissionerror','You are not authorized to perform this action.');
            return redirect()->route('admin.home');
        }

        $receipt->delete();

        return redirect()->route('admin.receipt.index');
    }

    public function covertToPdf($id)
    {
        if (! Gate::allows('hasPermission','manage_receipt')) {
            return abort(401);
        }
        $receipt = Receipt::where('id',$id)->with('invoice')->first();
    
        $pdf = PDF::loadView('admin.receipt.pdf',compact('receipt'));

        //return $pdf->stream();
        return $pdf->download('receipt-'.$receipt->invoice_id.'.pdf');

    }

    public function pdfAsMail(Request $request)
    {
        
        if (! Gate::allows('hasPermission','manage_receipt')) {
            return abort(401);
        }
        $receipt = Receipt::where('id',$request->receipt_id)->with('invoice')->first();
       
        $pdf = PDF::loadView('admin.receipt.pdf',compact('receipt'));
        
        $data['email'] = $request->email;
        $data['invoice'] = $receipt->invoice_id;
        // $path = public_path()."/pdf/receipt-".$receipt->invoice_id.".pdf";

        // $pdf->save($path);

        Mail::send('emails.pdfmail', $data, function($message)use($data,$pdf) {
            $message->to($data['email'],'User')
            ->subject('Receipt PDF')
            ->attachData($pdf->output(), "receipt-".$data['invoice'].".pdf");
        });

        if (Mail::failures()) {

            session()->flash('error','Email not Sent !');
            return back();

        }
        session()->flash('message','Email sent !');
        return redirect()->route('admin.receipts.index');

        //return $pdf->stream();
        //return $pdf->download('receipt-'.$receipt->invoice_id.'.pdf');

    }

    public function cash_transactions()
    {
        if (! Gate::allows('hasPermission','auth_cash_payment')) {
            return abort(401);
        }
        $receipts = Receipt::where('status','1')->with('invoice')->get();
    
        return view('admin.receipt.cash_payment',compact('receipts'));

    }

    public function cash_auth($id)
    {
        if (! Gate::allows('hasPermission','auth_cash_payment')) {
            return abort(401);
        }
        $receipt = Receipt::find($id);
        if(empty($receipt)){
            session()->flash('error','Something went wrong.');
            return redirect()->route('admin.receipts.cash');
        }
        
        $receipt->status = '2';
        $receipt->save();
        session()->flash('message','Authorized.');
        return redirect()->route('admin.receipts.cash');

    }


   


}
