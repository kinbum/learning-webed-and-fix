<?php namespace App\Module\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Menu\Models\Menu;
use App\Module\Menu\Models\MenuNode;
use App\Module\Menu\Repositories\Contracts\MenuNodeRepositoryContract;
use App\Module\Menu\Repositories\Contracts\MenuRepositoryContract;
use App\Module\Menu\Repositories\MenuNodeRepository;
use App\Module\Menu\Repositories\MenuNodeRepositoryCacheDecorator;
use App\Module\Menu\Repositories\MenuRepository;
use App\Module\Menu\Repositories\MenuRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Menu';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MenuRepositoryContract::class, function () {
            $repository = new MenuRepository(new Menu());

            if (config('ace-caching.repository.enabled')) {
                return new MenuRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
        $this->app->bind(MenuNodeRepositoryContract::class, function () {
            $repository = new MenuNodeRepository(new MenuNode());

            if (config('ace-caching.repository.enabled')) {
                return new MenuNodeRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
