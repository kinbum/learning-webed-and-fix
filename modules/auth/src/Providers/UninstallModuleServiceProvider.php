<?php namespace App\Module\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class UninstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Auth';

    protected $moduleAlias = 'auth';

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
    }

    private function dropSchema()
    {
        //If you want to rollback your module migration
        // uncomment bellow statement
         
//        \Artisan::call('module:migrate:rollback', ['alias' => $this->moduleAlias]);

    }
}
