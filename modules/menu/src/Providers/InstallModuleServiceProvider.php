<?php namespace App\Module\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Menu';

    protected $moduleAlias = 'menu';

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
        //Resolve your module dependency
        acl_permission()
            ->registerPermission('View menus', 'view-menus', $this->module)
            ->registerPermission('Delete menus', 'delete-menus', $this->module)
            ->registerPermission('Create menus', 'create-menus', $this->module)
            ->registerPermission('Edit menus', 'edit-menus', $this->module);
        $this->createSchema();
    }

    private function createSchema()
    {
        \Artisan::call('module:migrate', ['alias' => $this->moduleAlias]);
    }
}
