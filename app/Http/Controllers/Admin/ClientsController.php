<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Invoices;
use App\Order;
use App\User;
use App\Receipt;
use App\Client;
use DataTables;
use Helper;
use Auth;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.clients.index');
    }

    public function list(Request $request)
    {
         if (Gate::allows('all_clients')) {
        
            $data = Client::orderBy('id','DESC')->get();
			
			return DataTables::of($data)->addIndexColumn()->make(true);

        }
        $role = Auth::user()->roles->first()->name;

        if($role == 'sales man' || $role == 'sales person'){

            $data = Client::where('create_by',Auth::user()->id)->orderBy('id','DESC')->get();
        }elseif($role == 'administrator'){
            $data = Client::orderBy('id','DESC')->get();
        }else{
            $data ='';
        }
        return DataTables::of($data)->addIndexColumn()->make(true);
    }
	
	public function checkin(Request $request)
    {
		
		    if($request->ajax()){
            $data = Client::orderBy('id','DESC')->get();
            return DataTables::of($data)->addIndexColumn()->make(true);
			}
			
			return view('admin.clients.checkin');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		
        $data = $request->all();
        $fullpath = '';
        if($request->hasFile('avatar')){

            $file = $request->file('avatar');

            $name = time().'-'.$file->getClientOriginalName();

            $path = public_path('avatar');

            $file->move($path,$name);

            $fullpath ='/avatar/'.$name;
        }

        $data['avatar'] = $fullpath;
        $data['create_by'] = Auth::user()->id;

        $client = Client::create($data);
		
		$user = new User();
		$user->name = $client->name;
		$user->email = $client->email;
		$user->password = bcrypt($request->password);
		$user->mobile = $client->mobile;
		$user->type = '3';
		$user->client_code = $request->code;
		$user->save();
		Client::where('id',$client->id)->update(['user_id' => $user->id]);
		$user->assignRole('client');

        session()->flash('message','Data Inserted.');

        return redirect()->route('admin.clients.index');
    }

    public function importCSV(Request $request)
    {
        
        if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $file = $request->file('csv');

        $name = time().'-'.$file->getClientOriginalName();
        $path = public_path('import');

        $file->move($path,$name);

        $getfile = public_path('/import/'.$name);

        $all = $this->csvToArray($getfile);

        unlink($getfile);
           
        try {

            foreach($all as $key => $data){

                //$isDuplicate = Client::where('email',$data[1])->count();

                //if($isDuplicate < 1){

                    $client = new Client;
                    $client->code = $data[0];
                    $client->name = $data[1];
                    $client->direction = $data[2];
                    $client->mobile = $data[3];
                    $client->fax = $data[4];
                    $client->contact = $data[5];
                    $client->save();

                //}
            }

        
        } catch (\Exception $e) {
          
          session()->flash('error',$e->getMessage());

          return redirect()->back();
        }

        session()->flash('message','Data imported.');
       
        return redirect()->back();
        

    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        $i = 0;
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                $num = count($row);
                if (!$header)
                    $header = $row;
                else

                    for ($c=0; $c < $num; $c++) {
                        $data[$i][] = $row [$c];
                     }
                     $i++;

                    //$data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Display the Client profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		$client = Client::whereId($id)->first();
		
		// echo"<pre>";
		// print_r($client);
		// die;
        return view('admin.clients.profile',compact('client'));
    }
	
	/**
     * Display the Client profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoices(Request $request, $id)
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		$user = Client::find($id)->name;
		if($request->get('type') == 'all'){
			$invoices = Invoices::where('from_id',$id)->get();
		}else{
			$data = Invoices::where('from_id',$id)->get();
			$invoices = [];
			foreach($data as $invoice){
				$total_amount = $invoice->amount;
				$paid = Receipt::where('invoice_id',$invoice->invoice_no)->sum('total_payment');
				$amount = $total_amount-$paid;
				if($amount > 0){
					
					$invoices[] = $invoice;
					
				}
				
			}
		}
		
        return view('admin.clients.invoices',compact('invoices','user'));
    }
	
	/**
     * Display the Client Orders.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $id)
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		$user = Client::find($id)->name;
		$type = $request->get('type');
		if($request->ajax()){
			
			
			if($request->get('type') == 'all'){
				$data = Order::where('client',$id)->with('clients')->get();
			}else{
				$data = Order::where('client',$id)->where('status',0)->with('clients')->get();
			}
			
			return DataTables::of($data)->addIndexColumn()->make(true);
		}
		
        return view('admin.clients.orders',compact('id','type'));
    }
	
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.clients.edit',compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
         if (! Gate::allows('manage_client') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        $data = $request->all();
        $fullpath = '';
        if($request->hasFile('avatar')){

            $file = $request->file('avatar');

            $name = time().'-'.$file->getClientOriginalName();

            $path = public_path('avatar');

            $file->move($path,$name);

            $fullpath ='/avatar/'.$name;
			$data['avatar'] = $fullpath;
        }

        
        $client->update($data);
		$user = User::find($client->user_id);
		$user->name = $client->name;
		$user->email = $client->email;
		if($request->password){
		$user->password = bcrypt($request->password);
		}
		$user->mobile = $client->mobile;
		$user->client_code = $request->code;
		$user->save();

        session()->flash('message','Client Updated.');
       
        return redirect()->route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('manage_client') ) {
        
            session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
		
        $client = Client::find($id);
		
        User::where('user_id',$id)->delete();
		
		$client->delete();
		
        session()->flash('message','Client Deleted.');

        return redirect()->route('admin.clients.index');
    }
	
	 /**
     * Client Autocomplete Search
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function search(Request $request)
    {
        
        $clients = Client::select('name','id')->where('name', 'LIKE', "%{$request->get('term')}%") 
				->get();
		
        $response = array();
		foreach($clients as $client){
			 $response[] = array("value"=>$client->id,"label"=>$client->name);
		}		
       
       return response()->json($response);
    }
	
	/**
     * Client Products
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function products(Request $request, $id)
    {
        if($request->ajax()){
			
			$orders = Order::where('client',$id)->with('products')->orderBy('id','DESC')->get();
			$products = [];
			foreach($orders as $key => $order){
				foreach($order->products as $data){
					$data['order_created'] = $order->created_at;
					$data['invoice_created'] = Helper::getInvoiceDate($order->id);
					$products[] = $data;
				}
			}
			// echo"<pre>";
			// print_r($products);
			// die;
			return DataTables::of($products)->addIndexColumn()->make(true);
		}
       	
        return view('admin.clients.products',compact('id'));
      
    }
	
	public function transfer()
    {
        
       	$clients = Client::all();
		$c = 1;
		foreach($clients as $client){
			
			$user = new User();
			$user->name = $client->name;
			$user->password = bcrypt('12345');
			$user->mobile = $client->mobile;
			$user->type = '3';
			$user->client_code = $client->code;
			$user->save();
			Client::where('id',$client->id)->update(['user_id' => $user->id]);
			$user->assignRole('client');
			$c++;
			
			//User::where('id',$client->user_id)->update(['client_code' => $client->code]);
		}
		die('done');
      
    }
	
	public function listJson(Request $request)
    {
		$data = Client::where('name', 'like', '%' . $request->search . '%')->pluck('name','id');
		
		return response()->json($data);
	}
}
