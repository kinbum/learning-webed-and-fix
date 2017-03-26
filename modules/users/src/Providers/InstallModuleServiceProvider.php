<?php namespace App\Module\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Users';

    protected $moduleAlias = 'users';

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
            ->registerPermission('View users', 'view-users', $this->module)
            ->registerPermission('Create users', 'create-users', $this->module)
            ->registerPermission('Edit other users', 'edit-other-users', $this->module)
            ->registerPermission('Delete users', 'delete-users', $this->module)
            ->registerPermission('Force delete users', 'force-delete-users', $this->module);

        $this->createSchema();
    }

    private function createSchema()
    {
        \Artisan::call('module:migrate', ['alias' => $this->moduleAlias]);
    }
}
