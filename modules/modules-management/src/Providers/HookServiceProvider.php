<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\ModulesManagement\Hook\RegisterDashboardStats;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        add_action('webed-dashboard.index.stat-boxes.get', [RegisterDashboardStats::class, 'handle'], 22);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
