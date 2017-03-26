<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Base\Console\Commands\InstallCmsCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Callback when app booted
     *
     * @return void
     */
    private function booted()
    {
        $this->commands([
            InstallCmsCommand::class,
        ]);
    }
}
