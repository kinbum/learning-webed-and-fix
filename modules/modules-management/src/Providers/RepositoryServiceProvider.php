<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\ModulesManagement\Models\Plugins;
use App\Module\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;
use App\Module\ModulesManagement\Repositories\PluginsRepository;
use App\Module\ModulesManagement\Repositories\PluginsRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PluginsRepositoryContract::class, function () {
            $repository = new PluginsRepository(new Plugins());
            return $repository;
        });
    }
}
