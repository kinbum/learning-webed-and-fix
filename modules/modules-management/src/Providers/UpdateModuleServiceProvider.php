<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class UpdateModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\ModulesManagement';

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
        register_module_update_batches('webed-modules-management', [

        ]);
    }

    protected function booted()
    {
        load_module_update_batches('webed-modules-management');
    }
}
