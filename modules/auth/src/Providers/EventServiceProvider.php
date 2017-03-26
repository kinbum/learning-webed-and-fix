<?php namespace App\Module\Auth\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Module\Auth\Listeners\UserLoggedInListener;
use App\Module\Auth\Listeners\UserLoggedOutListener;
class EventServiceProvider extends ServiceProvider
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
        Event::listen('Illuminate\Auth\Events\Login', UserLoggedInListener::class);
        Event::listen('Illuminate\Auth\Events\Logout', UserLoggedOutListener::class);
    }
}
