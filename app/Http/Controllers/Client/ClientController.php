<?php

namespace App\Http\Controllers\Client;

use App\User;
use App\Order;
use App\Invoices;
use App\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use DB;
use DataTables;

class ClientController extends Controller
{
    /**
     * Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       return view('client.home');
    }

    /**
     * Orders List
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request)
    {
        //print_r(auth()->user()->clients->id);
		//die;
		if($request->ajax()){
			
	    
		$data = Order::where('client',auth()->user()->clients->id)->orderBy('id','DESC')->get();

       
        return DataTables::of($data)->addIndexColumn()->make(true);
			
		}
		
        return view('client.orders');
    }

    /**
     * Invoices List
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function invoices(Request $request)
    {
       
		
		$invoices = Invoices::where('from_id',auth()->user()->clients->id)->orderBy('id','DESC')->get();

        return view('client.invoices',compact('invoices'));
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $roles = Role::get()->pluck('name', 'name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, User $user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $user->update($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function permSelect(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        User::findOrFail(session()->get('selected_user'))->user_permissions()->sync($request->perm_id,false);
        
        return 'success';

    }

    public function permUnselect(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        User::findOrFail(session()->get('selected_user'))->user_permissions()->detach($request->perm_id);
        
        return 'success';

    }

    public function loadPerm(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        session()->put('selected_user',$request->user_id);

        $permissions = Permission::all();

        $selected = DB::table('permission_user')->where('user_id',$request->user_id)->pluck('permission_id')->all();
        return view('admin.users.permissions', compact('permissions','selected'));
    }
	
	public function userProfile($id)
    {
        // if (! Gate::allows('users_manage')) {
            // return abort(401);
        // }

        $user = User::find($id);

        
        return view('admin.users.profile', compact('user'));
    }
	
	public function changePassword(Request $request)
    {
		if($request->post()){
			
			$request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_password_confirmation' => ['same:new_password'],
           ]);
		   
		   User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
		   
		   return redirect()->route('client.home');
		   
		}else{
			
			return view('client.change_password');
			
		}
    }
	
	


}
