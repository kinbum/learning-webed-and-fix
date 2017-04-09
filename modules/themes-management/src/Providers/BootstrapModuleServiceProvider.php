<?php namespace App\Module\ThemesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\ThemesManagement';

    /**
     * Bootstrap the application services.
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
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function booted()
    {
        /**
         * Register to dashboard menu
         */
        \DashboardMenu::registerItem([
            'id' => 'themes-management',
            'priority' => 1002,
            'parent_id' => null,
            'heading' => null,
            'title' => 'Themes',
            'font_icon' => 'icon-magic-wand',
            'link' => route('themes.index.get'),
            'css_class' => null,
            'permissions' => ['view-themes'],
        ]);
        if (cms_theme_options()->count()) {
            \DashboardMenu::registerItem([
                'id' => 'theme-options',
                'priority' => 1002,
                'parent_id' => null,
                'heading' => null,
                'title' => 'Theme options',
                'font_icon' => 'icon-settings',
                'link' => route('theme-options.index.get'),
                'css_class' => null,
            ]);
        }
    }
}
