<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\ModulesManagement\Facades\ModulesManagementFacade;

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'modules-management');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'modules-management');
        /*Load migrations*/
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/modules-management',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/modules-management'),
        ], 'lang');
        $this->publishes([
            __DIR__ . '/../../config' => base_path('config'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../../resources/themes' => base_path(),
        ], 'public-assets');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Load helpers
        load_module_helpers(__DIR__);

        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(LoadModulesServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(BootstrapModuleServiceProvider::class);

        //Register related facades
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ModulesManagement', ModulesManagementFacade::class);
    }
}
