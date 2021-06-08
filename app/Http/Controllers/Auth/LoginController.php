<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
	
	public function login(Request $request)
    {
	
		if(isset($request->email)){
			$this->validate($request, [
				'email'           => 'required|email',
				'password'           => 'required',
			]);
		}else{
			$this->validate($request, [
				'code'           => 'required',
				'password'           => 'required',
			]);
		}

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
		
		if(isset($request->email)){
			
			$auth = Auth::attempt(['email' => $request->email, 'password' => $request->password ],$request->remember);
			$errors = new MessageBag(['email' => ['These credentials do not match our records.']]);
		}else{
			
			$auth = Auth::attempt(['client_code' => $request->code, 'password' => $request->password ],$request->remember);
			$errors = new MessageBag(['code' => ['These credentials do not match our records.']]);
			
		}

        if($auth) {
			
            $this->redirectPath();
			
        }  else {
			
            $this->incrementLoginAttempts($request);
            return redirect()->back()->withErrors($errors);
		}

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
	
	public function redirectPath()	
	{	

       $user = auth()->user();
       $role = $user->roles->first()->name;
	   
	   if($role == 'client'){
		   return '/client/home';
	   }else{
		   return '/admin/home';
	   }
			
	}

    
}
