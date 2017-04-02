<?php namespace App\Module\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Pages';

    protected $moduleAlias = 'pages';
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
        acl_permission()
            ->registerPermission('View pages', 'view-pages', $this->module)
            ->registerPermission('Create pages', 'create-pages', $this->module)
            ->registerPermission('Edit pages', 'edit-pages', $this->module)
            ->registerPermission('Delete pages', 'delete-pages', $this->module);
    }

}
