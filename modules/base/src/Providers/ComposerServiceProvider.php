<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
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
        view()->composer([
            'base::admin._partials.breadcrumbs' 
        ], 'App\Module\Base\Http\ViewComposers\AdminBreadcrumbsViewComposer');

        view()->composer([
            'base::admin._partials.header',
            'base::admin._partials.sidebar',
        ], 'App\Module\Base\Http\ViewComposers\BasePartialsViewComposer');
    }
}
