<?php namespace App\Module\Acl\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Acl\Models\Permission;
use App\Module\Acl\Models\Role;
use App\Module\Acl\Repositories\Contracts\PermissionRepositoryContract;
use App\Module\Acl\Repositories\Contracts\RoleRepositoryContract;
use App\Module\Acl\Repositories\PermissionRepository;
use App\Module\Acl\Repositories\PermissionRepositoryCacheDecorator;
use App\Module\Acl\Repositories\RoleRepository;
use App\Module\Acl\Repositories\RoleRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Acl';

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
        $this->app->bind(RoleRepositoryContract::class, function () {
            $repository = new RoleRepository(new Role);
            if (config('ace-caching.repository.enabled')) {
                return new RoleRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
        $this->app->bind(PermissionRepositoryContract::class, function () {
            $repository = new PermissionRepository(new Permission);

            if (config('ace-caching.repository.enabled')) {
                return new PermissionRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
