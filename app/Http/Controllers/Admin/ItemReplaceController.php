<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\ReplaceWarrantyItem;
use Auth;

class ItemReplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if (! Auth::user()->hasRole(['manager'] )) {
        if (! Gate::allows('replace_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        $items = ReplaceWarrantyItem::where('status','>','0')->orderBy('id','DESC')->groupBy('order_num')->get(); 
        return view('admin.warranty.index',compact('items'));
    }
    
    public function preview($order)
    {
        //if (! Auth::user()->hasRole(['manager'] )) {
        if (! Gate::allows('replace_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        $items = ReplaceWarrantyItem::where('order_num',$order)->where('status','>','0')->get();
        return view('admin.warranty.replace',compact('items'));
    }
	
	public function goods_receive($order)
    {
        //if (! Auth::user()->hasRole(['manager'] )) {
        if (! Gate::allows('replace_faulty_items')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }

        $items = ReplaceWarrantyItem::where(['status'=>'1','order_num'=>$order])->update(['status'=>'2']);
        return redirect()->route('admin.replace-item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
