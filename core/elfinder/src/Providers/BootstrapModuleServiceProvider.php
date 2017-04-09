<?php namespace App\Module\Elfinder\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Elfinder';

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
            'id' => 'elfinder',
            'priority' => 12,
            'parent_id' => null,
            'heading' => 'Others',
            'title' => 'Medias & Files',
            'font_icon' => 'icon-doc',
            'link' => route('elfinder.index.get'),
            'css_class' => null,
            'permissions' => ['view-files', 'crud-files'],
        ]);
    }
}
