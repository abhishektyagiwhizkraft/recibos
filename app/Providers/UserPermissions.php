<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Blade;

use App\User;

class UserPermissions extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('hasPermission', function($permission){

            if(Auth::user()){
                if(Auth::user()->hasThisPermission($permission)){

                    return true;
                }
            }

            return false;
        });
    }
}
