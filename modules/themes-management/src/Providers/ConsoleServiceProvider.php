<?php namespace App\Module\ThemesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->generatorCommands();
        $this->otherCommands();
    }

    private function generatorCommands()
    {
        $this->commands([
            \App\Module\ThemesManagement\Console\Generators\MakeTheme::class,
            \App\Module\ThemesManagement\Console\Generators\MakeController::class,
            \App\Module\ThemesManagement\Console\Generators\MakeView::class,
            \App\Module\ThemesManagement\Console\Generators\MakeProvider::class,
            \App\Module\ThemesManagement\Console\Generators\MakeCommand::class,
            \App\Module\ThemesManagement\Console\Generators\MakeCriteria::class,
        ]);
    }

    private function otherCommands()
    {
        $this->commands([
            \App\Module\ThemesManagement\Console\Commands\EnableThemeCommand::class,
            \App\Module\ThemesManagement\Console\Commands\DisableThemeCommand::class,
            \App\Module\ThemesManagement\Console\Commands\InstallThemeCommand::class,
            \App\Module\ThemesManagement\Console\Commands\UpdateThemeCommand::class,
            \App\Module\ThemesManagement\Console\Commands\UninstallThemeCommand::class,
            \App\Module\ThemesManagement\Console\Commands\GetAllThemesCommand::class,
        ]);
    }
}
