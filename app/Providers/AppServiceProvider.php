<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // if (!$this->app->isLocal()) {
        //     $this->app['request']->server->set('HTTPS', true);
        // }
        // error_log('MASIH BISA MASUk');
        // $this->registerPolicies();
        //

       // Implicitly grant "Super-Admin" role all permission checks using can()
    //    Gate::before(function ($user, $ability) {
    //        if ($user->hasRole('SuperAdmin')) {
    //            return true;
    //        }
    //    });
    }
}
