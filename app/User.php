<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Order;
use App\User;
use App\Invoices;
use App\Client;
use Helper;
use Hash;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = ['name', 'email', 'mobile', 'password', 'remember_token','type','comision','client_code'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
	
    public function clients()
    {
        return $this->hasOne(Client::class);
    }
	
    public function user_permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasThisPermission($checkpermission){
        $user = User::where('id',auth()->user()->id)->first();
       $permissionsArray = [];
       foreach ($user->user_permissions as $permission) {

                    $permissionsArray[] = $permission->name;
                
        }

        if(in_array($checkpermission, $permissionsArray)){
            return true;
        }
        return false;
    
    }
	
	public function invoice()
    {
        return $this->hasOne(Invoices::class,'id','created_by');
    }
	
	public static function ordersCount($id)
    {
        return Order::where('create_by',$id)->count();
    }
	
	public static function salesManClients($id)
    {
        return Client::where('create_by',$id)->count();
    }
	
	public static function clientSalesMan($id)
    {
		$client = Client::find($id);
		$user_id = $client->create_by;
		$user = User::find($user_id);
		$user_name = $user->name;
        return $user_name;
    }
	
	public static function totalEarnings($id)
    {
		
		$orders = Order::where(['create_by'=>$id])->get();
		
		$sum = 0;
		
		foreach($orders as $order){
			//$invoice = Invoices::where('order_id',$order->id)->first();
			$invoice = Invoices::where('order_id',$order->id)->whereMonth('created_at',date('m'))->first();
			if($invoice){
				$invoiceIsCompleted = Helper::paid($invoice);
				if($invoiceIsCompleted < 1){
				 //$comision = self::calComision($order->total,$id);
				 $comision = self::calComision($invoice->amount,$id);
				 $sum+= $comision;
				}else{
				  $sum+= 0;	
				}
			}
		}
		
		return (int)(($sum*100))/100;
    }
	
	
	public static function calComision($amount,$id)
    {
		$comision = User::find($id)->comision;
		$result = ($comision/100)*$amount;
		return $result;
	}
	public static function getUserName($id)
    {
        return self::whereId($id)->pluck('name')->first();
    }
	
	public static function isv($amount){
         $ret = ((15/100)*($amount));
         return $ret;
    }
    
    
    
}
