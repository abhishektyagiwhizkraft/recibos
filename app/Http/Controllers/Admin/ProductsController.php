<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Imports\ProductsImport;
use App\Product;
use App\Price;
use App\Helpers\SimpleXLS;
use DataTables;
use Excel;
use PDF;
use DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.products.index');
    }

    public function list(Request $request)
    {
        
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }  
        $data = Product::orderBy('id','DESC')->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        $data = $request->all();
		// echo"<pre>";
		// print_r($data);
		// die;
        $data['tax'] = $this->percentage($data['product'][1]['price']);
        $data['price'] = $data['product'][1]['price'];
        $product = Product::create($data);
		foreach($data['product'] as $price){
			$proPrice = new Price();
			$proPrice->product_id = $product->id;
			$proPrice->price = $price['price'];
			$proPrice->qty_from = $price['qty_from'];
			$proPrice->qty_to = $price['qty_to'];
			$proPrice->tax = $this->percentage($price['price']);
			$proPrice->save();
		}
		
        session()->flash('message','Product Inserted.');

        return redirect()->route('admin.products.index');
    }

    public function importCSV(Request $request)
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }

        $file = $request->file('csv');

        $name = time().'-'.$file->getClientOriginalName();
        $path = public_path('import');

        $file->move($path,$name);

        $getfile = public_path('/import/'.$name);
        //$xls = SimpleXLS::parse($getfile);
        //$data = Excel::import(new ProductsImport(), $request->file('csv'));

        $all = $this->csvToArray($getfile);
        // echo "<pre>";
        // print_r($all);
        // die;
        unlink($getfile);

        try {
            
            foreach($all as $data){
           
            $actual = ($data[3]/1.15);
			
			// print_r($data[2]);
			// echo"<pre>";
			// print_r($data[3]);
		    // die;

            $price = sprintf('%0.2f', $actual);

            $tax = $this->percentage($price);
            
            //$pro = new Product;
            // $pro = Product;
            // $pro->code        = $data[0];
            // $pro->description = $data[2];
            // $pro->tax = $tax;
            // $pro->price = $price;
            // $pro->save();

            }
        
        } catch (\Exception $e) {
          
          session()->flash('error',$e->getMessage());

          return redirect()->back();
        }

        session()->flash('message','Data imported.');

        return redirect()->back();
        

    }

    function percentage($amount){

            $per = ((15/100)*($amount));

            $r = sprintf('%0.2f', $per);

            return $r;

    }
	
	
	function updatePrice($data)
    {
		
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
    public function edit(Product $product)
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        return view('admin.products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateQty(Request $request){
		
		$pro = Product::where('id',$request->id)->first();
		$quantity = $pro->qty + $request->qty;
		
		$pro->qty = $quantity;
		$pro->save();
	}
	
	public function qtyComplete(Request $request){
		
		// echo "<pre>";
		// print_r($request->all());
		// die();
		//$pro = Product::where('id',$request->p_id)->first();
		//if($pro->qty > 0){
			
			DB::table('order_product')->where('product_id',$request->p_id)->where('order_id',$request->order_id)->where('need',$request->need)->update(['need' => 0]);
			session()->flash('message','Quantity completed.');
       
            
		//}else{
			//session()->flash('error','Product Quantity is less.');
		//}
		return redirect()->back();
		
	}
	
	
    public function update(Request $request, Product $product)
    {
        if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        
        $data = $request->all();
        $data['tax'] = $this->percentage($data['product'][1]['price']);
        $data['price'] = $data['product'][1]['price'];
        $product->update($data);
		$prices = Price::where('product_id',$product->id)->pluck('id')->all();
		
        $arr = [];
        foreach($data['product'] as $price){
			    if(isset($price['price_id'])){
				$proPrice = Price::find($price['price_id']);
				$arr[] = $price['price_id'];
				}else{
				$proPrice = new Price();
				}
				$proPrice->product_id = $product->id;
				$proPrice->price = $price['price'];
				$proPrice->qty_from = $price['qty_from'];
				$proPrice->qty_to = $price['qty_to'];
				$proPrice->tax = $this->percentage($price['price']);
				$proPrice->save();

				
		}
		$ids = array_diff($prices,$arr);
		if(count($ids)>0 && count($data['product']) < count($prices)){
			Price::whereIn('id',$ids)->delete();
		}
		
        session()->flash('message','Product Updated.');
       
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       if (! Gate::allows('manage_product') ) {
        
             session()->flash('permissionerror','You are not authorized to access this page.');
            return redirect()->route('admin.home');

        }
        
        Product::find($id)->delete();

        session()->flash('message','Product Deleted.');

        return redirect()->route('admin.products.index');
    }
	
	public function price()
    {
      
        
        $products = Product::where('id','!=',1254)->get();
        foreach($products as $product){
			$price = new Price();
			$price->product_id = $product->id;
			$price->price = $product->price;
			$price->qty_from = '1';
			$price->qty_to = '100';
			$price->tax = $product->tax;
			$price->save();
		}
		die('done');
        session()->flash('message','Product Deleted.');

        return redirect()->route('admin.products.index');
    }
}
