<?php namespace App\Module\Pages\Providers;

use Illuminate\Support\ServiceProvider;

class UninstallModuleServiceProvider extends ServiceProvider
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
        $this->dropSchema();
        acl_permission()
        ->unsetPermissionByModule($this->module);
    }

    private function dropSchema()
    {
       \Artisan::call('module:migrate:rollback', ['alias' => $this->moduleAlias]);
    }
}
