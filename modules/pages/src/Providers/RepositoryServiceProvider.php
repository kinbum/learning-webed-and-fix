<?php namespace App\Module\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Pages\Models\Page;
use App\Module\Pages\Repositories\Contracts\PageRepositoryContract;
use App\Module\Pages\Repositories\PageRepository;
use App\Module\Pages\Repositories\PageRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Pages';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PageRepositoryContract::class, function () {
            $repository = new PageRepository(new Page());

            return $repository;
        });

    }
}
