<?php namespace  App\Module\Caching\Providers;

use Illuminate\Support\ServiceProvider;
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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'caching');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'caching');

        \Event::listen(['cache:cleared'], function () {
            \File::delete(config('ace-caching.repository.store_keys'));
            \File::delete(storage_path('framework/cache/cache-service.json'));
        });

        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/caching',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/caching'),
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

    }
}
