<?php namespace App\Plugins\Blog\Providers;

use Illuminate\Support\ServiceProvider;

class UninstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\Blog';

    protected $moduleAlias = 'blog';
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
        ->unsetPermissionByModule($this->module);

        $this->dropSchema();
    }

    private function dropSchema()
    {
       \Artisan::call('module:migrate:rollback', ['alias' => $this->moduleAlias]);
    }
}
