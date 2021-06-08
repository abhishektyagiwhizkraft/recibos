<?php

namespace App\Http\Controllers\Admin;

use Helper;
use App\User;
use App\Permission;
use App\Invoices;
use App\Receipt;
use App\Order;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvoicesRequest;
use App\Http\Requests\Admin\UpdateInvoicesRequest;
use DB;

class InvoicesController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  

        
        if (! Gate::allows('manage_invoice') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		if($request->ajax()){
			if(!empty($request->start_date) && !empty($request->end_date)){
				$invoices = Invoices::whereBetween('created_at', [$request->start_date, $request->end_date])->orderBy('id','DESC')->get();
				
				$data = [];
				foreach($invoices as $invoice){
					$status = (Helper::paid($invoice) > 0) ? 'Pendiente' : 'Terminado';
					$invoice['paid_status'] = $status;
					$invoice['pending_amount'] = Helper::paid($invoice);
					$data[] = $invoice;
				}
			}else{
				$invoices = Invoices::orderBy('id','DESC')->get();
				$data = [];
				foreach($invoices as $invoice){
					$status = (Helper::paid($invoice) > 0) ? 'Pendiente' : 'Terminado';
					$invoice['paid_status'] = $status;
					$invoice['pending_amount'] = Helper::paid($invoice);
					$data[] = $invoice;
				}
			}
			// echo"<pre>";
			// print_r($data);
			// die;
			return DataTables::of($data)->addIndexColumn()->make(true);
			
		}

        $invoices = Invoices::orderBy('id','DESC')->get();
        

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('manage_invoice')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        return view('admin.invoices.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoicesRequest $request)
    {
        if (! Gate::allows('manage_invoice')) {

            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        
       $store = Invoices::store($request);

        return redirect()->route('admin.invoices.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoices $invoice)
    {
        if (! Gate::allows('manage_invoice')) {

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
        if (! Gate::allows('manage_invoice')) {
            
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
		
        $invoice->invoice_no = $request->invoice_no;
		$invoice->order_id = $request->order_id;
        $invoice->amount = $request->amount ;
        $invoice->due_date = $request->due_date ;
        $invoice->from = $request->from ;
        $invoice->from_id = $request->client_id ;
        $invoice->purpose = $request->purpose;
        $invoice->save();


        return redirect()->route('admin.invoices.index');
    }

    public function show(User $user)
    {
        if (! Gate::allows('manage_invoice')) {

            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoices $invoice)
    {
        if (! Gate::allows('manage_invoice')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
	
        Receipt::where('invoice_id',$invoice->invoice_no)->delete();
        $invoice->delete();

        return response()->json(['status' => 'success']);
    }

    public function sessoinsave(Request $request)
    {
        if (! Gate::allows('manage_invoice')) {
            return abort(401);
        }
        
        session()->put('payment_mode',$request->p_mode);
        session()->put('client_id',$request->client_id);
        session()->put('invoice_id',$request->invoice_id);

        return 'success';
    }
	public function history($id)
    {
        if (! Gate::allows('manage_invoice')) {

             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $invoice = Invoices::where('id',$id)->first();
        $order = [];
		if($invoice->order_id > 0){
			$order = Order::where('id',$invoice->order_id)->first();
		}

        return view('admin.invoices.show',compact('invoice','order'));
    }
	
	public function cancel($id)
    { 
	   
        if (auth()->user()->roles[0]->name != 'administrator') {
            return abort(401);
        }
        
        Invoices::where('id',$id)->update(['status' => '0']);

         session()->flash('message','Invoice canceled.');
         return redirect()->back();
    }

   


}
