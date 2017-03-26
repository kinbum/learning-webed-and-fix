<?php namespace  App\Module\Caching\Providers;

use Illuminate\Support\ServiceProvider;
use  App\Module\Caching\Services\CacheItemPool;
use  App\Module\Caching\Services\CacheService;
use  App\Module\Caching\Services\Contracts\CacheItemPoolContract;
use  App\Module\Caching\Services\Contracts\CacheServiceContract;
use Illuminate\Contracts\Cache\Repository as LaravelRepositoryCacheContract;

class ModuleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Load views*/
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'ace-caching');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'ace-caching');

        \Event::listen(['cache:cleared'], function () {
            \File::delete(config('ace-caching.repository.store_keys'));
            \File::delete(storage_path('framework/cache/cache-service.json'));
        });

        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/ace-caching',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/ace-caching'),
        ], 'lang');
        $this->publishes([
            __DIR__ . '/../../config' => base_path('config'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(BootstrapModuleServiceProvider::class);

        $this->mergeConfigFrom(__DIR__ . '/../../config/ace-caching.php', 'ace-caching');

        //Bind some services
        $this->app->bind(CacheItemPoolContract::class, function () {
            return new CacheItemPool($this->app->make(LaravelRepositoryCacheContract::class));
        });
        $this->app->bind(CacheServiceContract::class, CacheService::class);
    }
}
