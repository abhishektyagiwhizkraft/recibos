<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Client;
use App\Permission;
use App\Invoices;
use App\Receipt;
use App\Product;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReceiptsRequest;
use App\Http\Requests\Admin\UpdateReceiptsRequest;
use App\Exports\ReportsCashExport; 
use App\Exports\ReportsDepositExport; 
use App\Exports\ReportsChequeExport; 
use App\Exports\SalesByExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use PDF;
use DB;
use Mail;


class ReceiptsController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (! Gate::allows('manage_receipt') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }


        
        $receipts = Receipt::orderBy('id','DESC')->get();
        

        return view('admin.receipt.index', compact('receipts'));
    }

    public function exportCSV(Request $request)
    { 
      
      if (! Gate::allows('manage_receipt') ) {
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
		if($request->p_method != 'salesBy'){
			if($request->p_method == 'Efectivo'){
				session()->put('method',$request->p_method);
				$data = ['cash_date'=>$request->post_cash_date];

				session()->put('filter-cash',$data);

				return Excel::download(new ReportsCashExport, 'reportEfectivo.csv');
			}elseif($request->p_method == 'Cheque'){

				session()->put('method',$request->p_method);
				$data = ['bank'=>$request->post_bank, 'cheque_no'=>$request->post_cheque];
				session()->put('filter-cheque',$data);

				return Excel::download(new ReportsChequeExport, 'reportCheque.csv');
			 }elseif($request->p_method == 'Depositar'){

				session()->put('method',$request->p_method);
				$data = ['bank'=>$request->post_bank, 'reference_no'=>$request->post_reference];
				session()->put('filter-deposit',$data);

				return Excel::download(new ReportsDepositExport, 'reportDepositar.csv');
			}
		}else{
			
			    session()->put('method',$request->p_method);
				$data = ['by_client'=>$request->csv_by_client, 'by_product'=>$request->csv_by_product];
				session()->put('filter-salesby',$data);
                if(!empty($request->csv_by_client)){
					$name = 'salesByClient-'.$request->csv_by_client;
				}else{
					$name = 'salesByProduct-'.$request->csv_by_client;
				}
				return Excel::download(new SalesByExport, $name.'.csv');
			
		}
    }


    public function listAll(Request $request)
    {

       if(!empty($request->start_date) && !empty($request->end_date)){
        
            $data = Receipt::whereBetween('created_at', array($request->start_date, $request->end_date))->orderBy('id','DESC')->with('invoice')->get();
    

       }else{

            $data = Receipt::orderBy('id','DESC')->with('invoice')->get();

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
        if (! Gate::allows('manage_receipt')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        if(!empty(session()->get('invoice_id'))){

        $invoice = Invoices::find(session()->get('invoice_id'));    

        if(Helper::paid($invoice) < 1 ){
             session()->flash('error','Invoice #'.$invoice->invoice_no.' had been paid.');
             return redirect()->route('admin.invoices.index');
        }

        if(session()->get('payment_mode') == 'nota_de_credito'){
            $client = Client::whereId(session()->get('client_id'))->first();
			$invoice = session()->get('invoice_id');
			$products = Product::all();
			$vendors = User::whereHas(
									'roles', function($q){
										$q->where('name', 'sales person');
										$q->orWhere('name', 'sales man');
										$q->orWhere('name', 'Bodega');
									}
								)->get();
			//$vendors = User::where('type','2')->get();
            return view('admin.nc.create',compact('invoice','client','products','vendors'));
          
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
        if (! Gate::allows('manage_receipt')) {
            
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
       // $check = Receipt::where('invoice_id',session()->get('invoice_id'))->first();
       // if(! empty($check)){
       //   session()->flash('error','Receipt already generated for Invoice No. #'.session()->get("invoice_id"));
       //   return redirect()->route('admin.invoices.index');
       // }
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
        if (! Gate::allows('manage_receipt')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
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
        if (! Gate::allows('manage_receipt')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
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
        if (! Gate::allows('manage_receipt')) {
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
    public function destroy(User $user)
    {
        if (! Gate::allows('manage_receipt')) {
            
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }
	
	public function deleteReceipt($id)
    {
        if (! Gate::allows('manage_receipt')) {
            
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        Receipt::find($id)->delete();

        return redirect()->route('admin.users.index');
    }

    public function covertToPdf($id)
    {
        if (! Gate::allows('manage_receipt')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $receipt = Receipt::where('id',$id)->with('invoice')->first();
    
        $pdf = PDF::loadView('admin.receipt.pdf',compact('receipt'));

        //return $pdf->stream();
        return $pdf->download('receipt-'.$receipt->id.'.pdf');

    }

    public function pdfAsMail(Request $request)
    {
        
        if (! Gate::allows('manage_receipt')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $receipt = Receipt::where('id',$request->receipt_id)->with('invoice')->first();
       
        $pdf = PDF::loadView('admin.receipt.pdf',compact('receipt'));
        
        $data['email'] = $request->email;
        $data['invoice'] = $receipt->invoice_id;
        // $path = public_path()."/pdf/receipt-".$receipt->invoice_id.".pdf";

        // $pdf->save($path);

        Mail::send('emails.pdfmail', $data, function($message)use($data,$pdf) {
            $message->to($data['email'],'User')
            ->subject('Recibo De Pago')
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
        if (! Gate::allows('auth_cash_payment')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $receipts = Receipt::where('status','1')->with('invoice')->get();
    
        return view('admin.receipt.cash_payment',compact('receipts'));

    }

    public function cash_auth($id)
    {
        if (! Gate::allows('auth_cash_payment')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
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
