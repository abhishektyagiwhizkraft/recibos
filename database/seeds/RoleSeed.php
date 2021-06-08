<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(['users_manage','manage_invoice','manage_report','manage_receipt','auth_cash_payment','manage_web_setting']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'sales man']);
        Role::create(['name' => 'store keeper']);
        
    }
}
