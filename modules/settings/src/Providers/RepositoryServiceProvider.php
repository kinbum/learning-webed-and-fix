<?php namespace App\Module\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Settings\Models\EloquentSetting;
use App\Module\Settings\Repositories\SettingRepository;
use App\Module\Settings\Repositories\Contracts\SettingContract;
use App\Module\Settings\Repositories\SettingRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Settings';

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
        $this->app->bind(SettingContract::class, function () {
            $repository = new SettingRepository(new EloquentSetting);

            if (config('ace-caching.repository.enabled')) {
                return new SettingRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
