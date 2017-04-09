<?php namespace App\Module\ThemesManagement\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use App\Module\ThemesManagement\Facades\ThemeOptionsSupportFacade;
use App\Module\ThemesManagement\Facades\ThemesManagementFacade;

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'themes-management');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'themes-management');
        /*Load migrations*/
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/themes-management',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/themes-management'),
        ], 'lang');
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

        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('ThemesManagement', ThemesManagementFacade::class);
        $aliasLoader->alias('ThemeOptions', ThemeOptionsSupportFacade::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(LoadThemeServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(BootstrapModuleServiceProvider::class);
    }
}
