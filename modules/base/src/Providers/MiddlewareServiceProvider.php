<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Base\Http\Middleware\LoggedAdmin;
use App\Module\Base\Http\Middleware\AdminBarMiddleware;
use App\Module\Base\Http\Middleware\ConstructionModeMiddleware;
use App\Module\Base\Http\Middleware\CorsMiddleware;

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
        // $router->aliasMiddleware('ace.logged-admin', LoggedAdmin::class);
        if(!is_in_dashboard()) {
            $router->pushMiddlewareToGroup('web', ConstructionModeMiddleware::class);
            $router->pushMiddlewareToGroup('web', AdminBarMiddleware::class);
            $router->pushMiddlewareToGroup('api', CorsMiddleware::class);
        }
    }
}
