<?php namespace App\Module\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Settings';

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
         * Register dynamic menu or what you want when
         * bootstrap your module
         */
        \DashboardMenu::registerItem([
            'id' => 'ace-configuration',
            'priority' => 999,
            'parent_id' => null,
            'heading' => 'Advanced',
            'title' => 'Configurations',
            'font_icon' => 'icon-settings',
            'link' => route('settings.index.get'),
            'css_class' => null,
        ]);
        \DashboardMenu::registerItem([
            'id' => 'ace-settings',
            'priority' => 1,
            'parent_id' => 'ace-configuration',
            'heading' => null,
            'title' => 'Settings',
            'font_icon' => 'fa fa-circle-o',
            'link' => route('settings.index.get'),
            'css_class' => null,
            'permissions' => ['view-settings'],
        ]);
    }
}
