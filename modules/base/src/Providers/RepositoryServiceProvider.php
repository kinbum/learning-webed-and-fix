<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Base\Models\ViewTracker;
use App\Module\Base\Repositories\Contracts\ViewTrackerRepositoryContract;
use App\Module\Base\Repositories\ViewTrackerRepository;
use App\Module\Base\Repositories\ViewTrackerRepositoryCacheDecorator;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ViewTrackerRepositoryContract::class, function () {
            $repository = new ViewTrackerRepository(new ViewTracker());

            return $repository;
        });
    }
}
