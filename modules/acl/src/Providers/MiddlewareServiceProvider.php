<?php namespace App\Module\Acl\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Module\Acl\Http\Middleware\HasPermission;
use App\Module\Acl\Http\Middleware\HasRole;

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

        $router->aliasMiddleware('has-role', HasRole::class);
        $router->aliasMiddleware('has-permission', HasPermission::class);
    }
}
