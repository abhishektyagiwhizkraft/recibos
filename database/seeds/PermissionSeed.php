<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:clear');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Permission::create(['name' => 'users_manage']);
        Permission::create(['name' => 'manage_invoice']);
        Permission::create(['name' => 'manage_report']);
        Permission::create(['name' => 'manage_receipt']);
        Permission::create(['name' => 'auth_cash_payment']);
        Permission::create(['name' => 'manage_web_setting']);
    }
}
