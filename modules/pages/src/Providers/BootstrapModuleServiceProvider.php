<?php namespace App\Module\Pages\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Pages';

    /**
     * Bootstrap the application services.
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
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function booted()
    {
        /**
         * Register to dashboard menu
         */
        \DashboardMenu::registerItem([
            'id' => 'ace-pages',
            'priority' => 1,
            'parent_id' => null,
            'heading' => 'CORE',
            'title' => 'Pages',
            'font_icon' => 'icon-notebook',
            'link' => route('pages.index.get'),
            'css_class' => null,
            'permissions' => ['view-pages'],
        ]);
    }
}
