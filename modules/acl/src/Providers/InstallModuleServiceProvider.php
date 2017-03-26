<?php namespace App\Module\Acl\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Acl';

    protected $moduleAlias = 'acl';

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

        $this->createSchema();
    }

    private function createSchema()
    {
        \Artisan::call('module:migrate', ['alias' => $this->moduleAlias]);
        acl_permission()
            ->registerPermission('View roles', 'view-roles', $this->module)
            ->registerPermission('Create roles', 'create-roles', $this->module)
            ->registerPermission('Edit roles', 'edit-roles', $this->module)
            ->registerPermission('Delete roles', 'delete-roles', $this->module)
            ->registerPermission('View permissions', 'view-permissions', $this->module)
            ->registerPermission('Assign roles', 'assign-roles', $this->module);
    }
}
