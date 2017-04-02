<?php namespace App\Module\Users\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Users\Models\User;
use App\Module\Users\Repositories\UserRepositoryCacheDecorator;
use App\Module\Users\Repositories\UserRepository;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;


class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Users';

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
        $this->app->bind(UserRepositoryContract::class, function () {
            $repository = new UserRepository(new User);
            return $repository;
        });
    }
}
