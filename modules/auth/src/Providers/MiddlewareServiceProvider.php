<?php namespace App\Module\Auth\Providers;

use Illuminate\Support\ServiceProvider;

use  App\Module\Auth\Http\Middleware\AuthenticateAdmin;
use  App\Module\Auth\Http\Middleware\GuestAdmin;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Callback when app booted
     *
     * @return void
     */
    private function booted()
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('ace.auth-admin', AuthenticateAdmin::class);
        $router->aliasMiddleware('ace.guest-admin', GuestAdmin::class);
        $router->pushMiddlewareToGroup('web', AuthenticateAdmin::class);
    }
}
