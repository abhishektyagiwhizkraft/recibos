<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreFaultyItem;
use App\ReplaceWarrantyItem;
use Auth;
use Mail;
use PDF;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (! Gate::allows('pick_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        $items = ReplaceWarrantyItem::orderBy('id','DESC')->groupBy('order_num')->get();


        return view('admin.warranty.index',compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('pick_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        
        return view('admin.warranty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('pick_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
		
		
        $ordern = ReplaceWarrantyItem::max('order_num');
        if($ordern){
            $order = $ordern+1;
        }else{
            $order = 1000;
        }
        $data = $request->all();
        foreach($data['items'] as $itms){
			
        $url = sha1(uniqid(time(), true));
        $data['unique_url'] = $url;
        $data['received_by'] = auth()->user()->id;
        $item = new ReplaceWarrantyItem;
        $item->order_num = $order;
        $item->item = $itms['item'];
        $item->fault = $itms['fault'];
        $item->qty = $itms['qty'];
        $item->client_mobile = $data['client_mobile'];
        $item->client_name = $data['client_name'];
        $item->unique_url = $data['unique_url'];
        $item->received_by = $data['received_by'];
        $item->status = '1';
        $item->save();
        }
        session()->flash('message','Item Saved !');
        return redirect()->route('admin.warranty-items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function receiveFromWarehouse($order)
    {
        if (! Gate::allows('pick_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        
        $items = ReplaceWarrantyItem::where(['status'=>'2','order_num'=>$order])->update(['status'=>'3']);
        return redirect()->route('admin.warranty-items.index');
    }
    public function show($id)
    {
        //
    }
    
    public function mail_receipt(Request $request)
    {
        $data = $request->all();
        $order = $request->order_num;

        $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
       
        $customPaper = array(0,0,800,1440);

        $pdf = PDF::loadView('client_receipt',compact('items','order'))->setPaper($customPaper, 'portrait');
        $email = $request->email;
        
        Mail::send('emails.mailtoclient', $data, function($message)use($data,$pdf) {
            $message->to($data['email'],'User')
            ->subject('Detalle de artículos - LUMINOTEC')
            ->attachData($pdf->output(), "receipt-".$data['order_num'].".pdf");
        });

        if (Mail::failures()) {

            session()->flash('error','Email not Sent ! Please Try Again');
            return back();

        }
        
        ReplaceWarrantyItem::where(['order_num'=>$order])->update(['mail_sent'=> 1]);
        session()->flash('message','Email sent !');
        return redirect('/admin/warranty-items');
    }
	
	public function replaced_receipt(Request $request)
    {
        $data = $request->all();
        $order = $request->order_num;

        $items = ReplaceWarrantyItem::where(['order_num'=>$order,'replaced_status' => 1,'mail_status'=> 0])->get();
       
        $customPaper = array(0,0,800,1440);

        $pdf = PDF::loadView('replace_receipt',compact('items','order'))->setPaper($customPaper, 'portrait');
        $email = $request->email;
        
        Mail::send('emails.mailtoclient', $data, function($message)use($data,$pdf) {
            $message->to($data['email'],'User')
            ->subject('Artículos reemplazados - LUMINOTEC')
            ->attachData($pdf->output(), "receipt-".$data['order_num'].".pdf");
        });

        if (Mail::failures()) {

            session()->flash('error','Email not Sent ! Please Try Again');
            return back();

        }
        
        ReplaceWarrantyItem::where(['order_num'=>$order])->update(['mail_sent'=> 1]);
        session()->flash('message','Email sent !');
        return redirect('/admin/warranty-items');
    }
	
	

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItem($order)
    {
        ReplaceWarrantyItem::where(['order_num'=>$order])->delete();
        session()->flash('message','Items Deleted.');
            return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
