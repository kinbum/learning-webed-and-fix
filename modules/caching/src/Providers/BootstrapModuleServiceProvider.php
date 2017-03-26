<?php namespace  App\Module\Caching\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = ' App\Module\Caching';

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

    private function booted()
    {
        $this->registerMenu();
    }

    private function registerMenu()
    {
        // \DashboardMenu::registerItem([
        //     'id' => 'webed-caching',
        //     'priority' => 2,
        //     'parent_id' => 'webed-configuration',
        //     'heading' => null,
        //     'title' => 'Caching',
        //     'font_icon' => 'fa fa-circle-o',
        //     'link' => route('admin::webed-caching.index.get'),
        //     'css_class' => null,
        //     'permissions' => ['view-cache'],
        // ]);
    }
}
