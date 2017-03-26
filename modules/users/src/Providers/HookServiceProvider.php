<?php namespace App\Module\Users\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Users\Hook\RegisterDashboardStats;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       add_action('dashboard.index.stat-boxes.get', [RegisterDashboardStats::class, 'handle'], 24);
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
