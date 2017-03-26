<?php namespace App\Module\Users\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Users';

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
        \DashboardMenu::registerItem([
            'id' => 'ace-users',
            'priority' => 3,
            'parent_id' => null,
            'heading' => 'User & ACL',
            'title' => 'Users',
            'font_icon' => 'icon-users',
            'link' => route('users.index.get'),
            'css_class' => null,
            'permissions' => ['view-users'],
        ]);
    }
}
