<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\ModulesManagement';

    protected $moduleAlias = 'modules-management';

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

    private function booted()
    {
        acl_permission()
            ->registerPermission('View plugins', 'view-plugins', $this->module);
    }
}
