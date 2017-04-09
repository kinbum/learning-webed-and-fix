<?php namespace App\Module\ThemesManagement\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\ThemesManagement\Models\Theme;
use App\Module\ThemesManagement\Models\ThemeOption;
use App\Module\ThemesManagement\Repositories\Contracts\ThemeOptionRepositoryContract;
use App\Module\ThemesManagement\Repositories\Contracts\ThemeRepositoryContract;
use App\Module\ThemesManagement\Repositories\ThemeOptionRepository;
use App\Module\ThemesManagement\Repositories\ThemeOptionRepositoryCacheDecorator;
use App\Module\ThemesManagement\Repositories\ThemeRepository;
use App\Module\ThemesManagement\Repositories\ThemeRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\ThemesManagement';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ThemeRepositoryContract::class, function () {
            $repository = new ThemeRepository(new Theme());

            return $repository;
        });
        $this->app->bind(ThemeOptionRepositoryContract::class, function () {
            $repository = new ThemeOptionRepository(new ThemeOption());

            return $repository;
        });
    }
}
