<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Client;
use DataTables;
use App\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (! Gate::allows('users_manage') ) {
        
            return abort(401);

        }

        $users = User::where('type','2')->get();
        

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::where('name','<>','client')->get()->pluck('name', 'name');
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $user = User::create($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        return redirect()->route('admin.users.index');
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
	
	public function userClients(Request $request)
    {
      
        if($request->ajax()){
			

		 $data = Client::where('create_by',auth()->user()->id)->orderBy('id','DESC')->get();
		
		 return DataTables::of($data)->addIndexColumn()->make(true);
		 
		}

        
        return view('admin.salesman.clients');
    }
	
	public function userOrders(Request $request)
    {
      
        if($request->ajax()){
			

		 $data = Order::where('create_by',auth()->user()->id)->orderBy('id','DESC')->with('clients')->get();
		
		 return DataTables::of($data)->addIndexColumn()->make(true);
		 
		}

        
        return view('admin.salesman.orders');
    }
	
	public function clientsSalesman(Request $request,$id)
    {
      
        if($request->ajax()){
			

		 $data = Client::where('create_by',$id)->orderBy('id','DESC')->get();
		
		 return DataTables::of($data)->addIndexColumn()->make(true);
		 
		}

        
        return view('admin.users.clients',compact('id'));
    }
	
	public function ordersSalesman(Request $request,$id)
    {
      
        if($request->ajax()){
			

		 $data = Order::where('create_by',$id)->orderBy('id','DESC')->with('clients')->get();
		
		 return DataTables::of($data)->addIndexColumn()->make(true);
		 
		}

        
        return view('admin.users.orders',compact('id'));
    }



}
