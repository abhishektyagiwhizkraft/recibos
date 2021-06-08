<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Setting;
use Config;

class ConfigMailProvider extends ServiceProvider
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
        if (\Schema::hasTable('settings')) {
        $smtp_email = Setting::where('setting_key','smtp_email')->pluck('setting_value')->first();
        $smtp_password = Setting::where('setting_key','smtp_password')->pluck('setting_value')->first();
        $smtp_driver = Setting::where('setting_key','smtp_driver')->pluck('setting_value')->first();
        $smtp_host = Setting::where('setting_key','smtp_host')->pluck('setting_value')->first();
        $smtp_port = Setting::where('setting_key','smtp_port')->pluck('setting_value')->first();
        $smtp_encryption = Setting::where('setting_key','smtp_encryption')->pluck('setting_value')->first();
        
           
                $config = array(
                    'driver'     => $smtp_driver,
                    'host'       => $smtp_host,
                    'port'       => $smtp_port,
                    'from'       => array('address' => $smtp_email, 'name' => 'Receipts'),
                    'encryption' => $smtp_encryption,
                    'username'   => $smtp_email,
                    'password'   => $smtp_password,
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
            }
    }
}
