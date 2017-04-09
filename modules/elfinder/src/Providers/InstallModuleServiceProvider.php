<?php namespace App\Module\Elfinder\Providers;

use Illuminate\Support\ServiceProvider;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Elfinder';

    protected $moduleAlias = 'elfinder';

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
            ->registerPermission('View files', 'view-files', $this->module)
            ->registerPermission('Upload, Edit, Delete files', 'crud-files', $this->module);
        $this->createSchema();
    }

    private function createSchema()
    {
        \Artisan::call('module:migrate', ['alias' => $this->moduleAlias]);
    }
}
