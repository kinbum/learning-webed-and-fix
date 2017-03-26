<?php namespace App\Module\Acl\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Acl';

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
            'id' => 'ace-acl-roles',
            'priority' => 3.1,
            'parent_id' => null,
            'heading' => null,
            'title' => 'Roles',
            'font_icon' => 'icon-lock',
            'link' => route('acl-roles.index.get'),
            'css_class' => null,
            'permissions' => ['view-roles'],
        ])->registerItem([
            'id' => 'ace-acl-permissions',
            'priority' => 3.2,
            'parent_id' => null,
            'heading' => null,
            'title' => 'Permissions',
            'font_icon' => 'icon-shield',
            'link' => route('acl-permissions.index.get'),
            'css_class' => null,
            'permissions' => ['view-permissions'],
        ]);
    }
}
