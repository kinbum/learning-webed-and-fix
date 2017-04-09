<?php namespace App\Module\Base\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App\Module\Base\Facades\FlashMessagesFacade;
use App\Module\Base\Exceptions\Handler;
use App\Module\Base\Support\Helper;
use App\Module\Base\Facades\BreadcrumbsFacade;
use App\Module\Base\Facades\AdminBarFacade;

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'base');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'base');
        /*Load migrations*/
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../resources/assets' => resource_path('assets'),
            __DIR__ . '/../../resources/public' => public_path(),
        ], 'assets');
        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/base',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/base'),
        ], 'lang');
        $this->publishes([
            __DIR__ . '/../../database' => base_path('database'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Load helpers
        Helper::loadModuleHelpers(__DIR__);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        AliasLoader::getInstance()->alias('Form', \Collective\Html\FormFacade::class);
        AliasLoader::getInstance()->alias('Html', \Collective\Html\HtmlFacade::class);
        AliasLoader::getInstance()->alias('AdminBar', AdminBarFacade::class);
        AliasLoader::getInstance()->alias('FlashMessages', FlashMessagesFacade::class);
        AliasLoader::getInstance()->alias('Breadcrumbs', BreadcrumbsFacade::class);

        // Base
        $this->app->register(ComposerServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(CollectiveServiceProvider::class);
        $this->app->register(ValidateServiceProvider::class);
        $this->app->register(MiddlewareServiceProvider::class);
        // $this->app->register(HookServiceProvider::class);
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(BootstrapModuleServiceProvider::class);

        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);


        /**
         * Other module providers
         */
        // $this->app->register(\WebEd\Base\Shortcode\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Caching\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Acl\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\ModulesManagement\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\AssetManagement\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Auth\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Elfinder\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Hook\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Menu\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Settings\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\ThemesManagement\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Users\Providers\ModuleProvider::class);
        $this->app->register(\App\Module\Pages\Providers\ModuleProvider::class);
        $this->app->register(\App\Plugins\Blog\Providers\ModuleProvider::class);
        // $this->app->register(\App\Plugins\CustomFields\Providers\ModuleProvider::class);


        $this->mergeConfigFrom(__DIR__ . '/../../config/ace.php', 'ace');
        $this->mergeConfigFrom(__DIR__ . '/../../config/ace-templates.php', 'ace-templates');
    }

    // protected function loadHelpers()
    // {
    //     $helpers = $this->app['files']->glob(__DIR__ . '/../../helpers/*.php');
    //     foreach ($helpers as $helper) {
    //         require_once $helper;
    //     }
    // }
}
