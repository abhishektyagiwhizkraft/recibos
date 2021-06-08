<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ReplaceWarrantyItem;
use App\Order;
use PDF;
class ItemActionController extends Controller
{
    public function confirmation_client($order,$token){
        // if(auth()->check()){
        //     return abort(403, 'Unauthorized');
        // }
        $checktoken = ReplaceWarrantyItem::where(['unique_url'=>$token,'order_num'=>$order])->first();
        
        if(!$checktoken){
            return abort(403, 'This Link Expired');
        }
        
        if($checktoken->status == 0){
             $all = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($all as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $update->status = '1';
            $update->save();
           }
            
        }elseif($checktoken->status == 1){
            
            $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($items as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $url = sha1(uniqid(time(), true));
            $update->status = '1';
            $update->client_confirm = 1;
            $update->unique_url = $url;
            $update->save();

            
                
             }
             
            //  $customPaper = array(0,0,800,1440);
            // $pdf = PDF::loadView('client_receipt',compact('items','order'))->setPaper($customPaper, 'portrait');
           
            // //return $pdf->stream();
            //  return $pdf->download('order-'.$order.'.pdf');

        }
        
        return view('admin.warranty.thanks');
      }
	  
	  public function replaced($order,$token){
        // if(auth()->check()){
        //     return abort(403, 'Unauthorized');
        // }
        $checktoken = ReplaceWarrantyItem::where(['unique_url'=>$token])->first();
        
        if(!$checktoken){
            return abort(403, 'This Link Expired');
        }
        
        if($checktoken->status == '3'){
           
             $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($items as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $url = sha1(uniqid(time(), true));
            $update->status = '4';
            $update->save();

            }
        }elseif($checktoken->status == '4'){
            
            $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($items as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $url = sha1(uniqid(time(), true));
            $update->status = '5';
            $update->unique_url = null;
            $update->save();
             
            
                
             }
             
        }
        
        return view('admin.warranty.thanks');
      }

      public function confirmation_salesman($order,$token){

        $checktoken = ReplaceWarrantyItem::where('unique_url',$token)->first();

        if(!$checktoken){
            return abort(403, 'This Link Expired');
        }
        if($checktoken->status == '1'){
            // $url = sha1(uniqid(time(), true));
            // $checktoken->status = '2';
            // $checktoken->save();
            
            $all = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($all as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $update->status = '2';
            $update->save();
           }
        
        }elseif($checktoken->status == 2){
            // $url = sha1(uniqid(time(), true));
            // $checktoken->status = '2';
            // $checktoken->sales_confirm = 1;  
            // $checktoken->unique_url = $url;
            // $checktoken->save();
            
            $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
           foreach($items as $item){
            $update = ReplaceWarrantyItem::find($item->id);
            $url = sha1(uniqid(time(), true));
            $update->status = '2';
            $update->sales_confirm = 1;
            $update->unique_url = $url;
            $update->save();

            
                
             }
			 
			$customPaper = array(0,0,800,1440);
            $pdf = PDF::loadView('salesman_receipt',compact('items','order'))->setPaper($customPaper, 'portrait');
           
            ///return $pdf->stream();
            return $pdf->download('order-'.$order.'.pdf');
        }
            
        
        
        return view('admin.warranty.thanks');
      }

      public function confirmation_replace($token){

        $checktoken = ReplaceWarrantyItem::where('unique_url',$token)->first();

        if(!$checktoken){
            return abort(403, 'This Link Expired');
        }
        
        if($checktoken->status == '3'){
             $checktoken->status = '3';
             $checktoken->save();
        }elseif($checktoken->status == '3'){
        $url = sha1(uniqid(time(), true));
        $checktoken->sales_confirm = 2; 
        $checktoken->status = '3';
        $checktoken->unique_url = $url;
        $checktoken->replaced_status = 1;
        $checktoken->save();
       
        }
       return view('admin.warranty.thanks');
      }
      
      public function download($order,$token){
          
          $checktoken = ReplaceWarrantyItem::where('unique_url',$token)->first();

            if(!$checktoken){
                return abort(403, 'This Link Expired');
            }
           if($checktoken->mail_status == 0){
               $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
               foreach($items as $item){
                $update = ReplaceWarrantyItem::find($item->id);
               
                $update->mail_status = 1;
               
                $update->save();
    
               }
           }else{
               
          
           
                   $items = ReplaceWarrantyItem::where(['order_num'=>$order])->get();
                   foreach($items as $item){
                    $update = ReplaceWarrantyItem::find($item->id);
                    $url = sha1(uniqid(time(), true));
                    $update->unique_url = $url;
                    $update->mail_status = 2;
                    $update->save();
        
                   }
                
             }
			 
			$customPaper = array(0,0,800,1440);
            $pdf = PDF::loadView('client_receipt',compact('items','order'))->setPaper($customPaper, 'portrait');
           
            ///return $pdf->stream();
            return $pdf->download('order-'.$order.'.pdf');
      }
       public function uploadImg(Request $request){
           
           $response = Order::uploadImg($request);
           
           return response()->json($response);
           
       }

      
}
