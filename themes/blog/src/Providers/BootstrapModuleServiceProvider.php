<?php namespace App\Themes\Blog\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Themes\Blog';

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
        /*\DashboardMenu::registerItem([
            'id' => 'blog',
            'priority' => 20,
            'parent_id' => null,
            'heading' => null,
            'title' => 'Ace blog',
            'font_icon' => 'icon-puzzle',
            'link' => '',
            'css_class' => null,
        ]);*/
    }
}
