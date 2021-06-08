<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRolesRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;
use App\InvoiceFormat;
use DataTables;
use App\Setting;
use App\User;
use Validator;


class SettingsController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        if (! Gate::allows('manage_web_setting')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
             return redirect()->route('admin.home');
        }
		
		if($request->ajax()){
			
		   $formats = InvoiceFormat::get();
           return DataTables::of($formats)->addIndexColumn()->make(true);
			
		}
		$admin_email =   Setting::where('setting_key','admin_email')->pluck('setting_value')->first();
		$smtp_email =   Setting::where('setting_key','smtp_email')->pluck('setting_value')->first();
		$smtp_password =   Setting::where('setting_key','smtp_password')->pluck('setting_value')->first();
		$smtp_driver =   Setting::where('setting_key','smtp_driver')->pluck('setting_value')->first();
		$smtp_host =   Setting::where('setting_key','smtp_host')->pluck('setting_value')->first();
		$smtp_port =   Setting::where('setting_key','smtp_port')->pluck('setting_value')->first();
		$smtp_encryption =   Setting::where('setting_key','smtp_encryption')->pluck('setting_value')->first();
        //$receipt_address =   Setting::where('setting_key','receipt_address')->pluck('setting_value')->first();
        //$address = unserialize($receipt_address);
		//echo "<pre>";
		//print_r($address);
		//die;
		
 
        return view('admin.settings.index', compact(['admin_email','smtp_email','smtp_password','smtp_driver','smtp_host','smtp_port','smtp_encryption']));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $permissions = Permission::get()->pluck('name', 'name');

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (! Gate::allows('manage_web_setting')) {
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');
        }
        $data = $request->all();
       
        if(!empty($data['admin_email'])){
           Setting::where('setting_key','admin_email')->update(['setting_value' => $data['admin_email']]);
         
        }
        if(!empty($data['smtp_email'])){
           Setting::where('setting_key','smtp_email')->update(['setting_value' => $data['smtp_email']]);
         
        }
        if(!empty($data['smtp_password'])){
           Setting::where('setting_key','smtp_password')->update(['setting_value' => $data['smtp_password']]);
         
        }
        if(!empty($data['address'])){
           $serialize = serialize($data['address']);
           Setting::where('setting_key','receipt_address')->update(['setting_value' => $serialize]);
         
        }
        if(!empty($data['smtp_port'])){
           Setting::where('setting_key','smtp_port')->update(['setting_value' => $data['smtp_port']]);
         
        }
        if(!empty($data['smtp_host'])){
           Setting::where('setting_key','smtp_host')->update(['setting_value' => $data['smtp_host']]);
         
        }
        if(!empty($data['smtp_driver'])){
           Setting::where('setting_key','smtp_driver')->update(['setting_value' => $data['smtp_driver']]);
         
        }

        if(!empty($data['smtp_encryption'])){
           Setting::where('setting_key','smtp_encryption')->update(['setting_value' => $data['smtp_encryption']]);
         
        }


        return redirect()->route('admin.settings.index');
    }
	
	
	
	public function comision(Request $request)
    {
		
		if($request->ajax()){
			
			$update = User::where('id',$request->user_id)->update(['comision' => $request->com_value]);
			
			return response()->json(['success' => true]);
		}
		$users = User::whereHas('roles', function($q){
                                          $q->where('name','sales man');
                                          $q->orWhere('name','sales person');
                                        })->get();
										
	    return view('admin.settings.comision',compact('users'));
	    
	}
	
	public function facturaFormat(Request $request)
    {
		// $validator = Validator::make($request->all(), [
            // 'cia_number' => 'required|unique:invoice_formats',
        // ]);
        
        // if($validator->fails())
        // {
			// return response()->json(['success'=>false]);
		// }
		$save                      = new InvoiceFormat();
		$save->cia_number          = $request->cia_number;
		$save->start_emission_date = $request->start_emission_date;
		$save->end_emission_date   = $request->end_emission_date;
		$save->document_type       = $request->document_type;
		$save->format_number       = $request->format_number;
		$save->start_number        = $request->start_number;
		$save->end_number          = $request->end_number;
		$save->save();
		
		return response()->json(['success'=>true]);
	    
	}
	public function facturaFormatUpdate(Request $request)
    {
		// $validator = Validator::make($request->all(), [
            // 'cia_number' => 'required|unique:invoice_formats',
        // ]);
        
        // if($validator->fails())
        // {
			// return response()->json(['success'=>false]);
		// }
		$save                      = InvoiceFormat::find($request->id);
		$save->cia_number          = $request->cia_number;
		$save->start_emission_date = $request->start_emission_date;
		$save->end_emission_date   = $request->end_emission_date;
		$save->document_type       = $request->document_type;
		$save->format_number       = $request->format_number;
		$save->start_number        = $request->start_number;
		$save->end_number          = $request->end_number;
		$save->save();
		
		return response()->json(['success'=>true]);
	    
	}



}
