<?php namespace App\Module\ModulesManagement\Providers;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;
use App\Module\ModulesManagement\Services\ModuleMigrator;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\ModulesManagement';

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
        $this->migrationCommands();
        $this->otherCommands();
    }

    protected function generatorCommands()
    {
        $this->commands([
            \App\Module\ModulesManagement\Console\Generators\MakeModule::class,
            \App\Module\ModulesManagement\Console\Generators\MakeProvider::class,
            \App\Module\ModulesManagement\Console\Generators\MakeController::class,
            \App\Module\ModulesManagement\Console\Generators\MakeMiddleware::class,
            \App\Module\ModulesManagement\Console\Generators\MakeRequest::class,
            \App\Module\ModulesManagement\Console\Generators\MakeModel::class,
            \App\Module\ModulesManagement\Console\Generators\MakeRepository::class,
            \App\Module\ModulesManagement\Console\Generators\MakeFacade::class,
            \App\Module\ModulesManagement\Console\Generators\MakeService::class,
            \App\Module\ModulesManagement\Console\Generators\MakeSupport::class,
            \App\Module\ModulesManagement\Console\Generators\MakeView::class,
            \App\Module\ModulesManagement\Console\Generators\MakeMigration::class,
            \App\Module\ModulesManagement\Console\Generators\MakeCommand::class,
            \App\Module\ModulesManagement\Console\Generators\MakeDataTable::class,
            \App\Module\ModulesManagement\Console\Generators\MakeCriteria::class,
            \App\Module\ModulesManagement\Console\Generators\MakeAction::class,
        ]);
    }

    /**
     * register database migrate related command
     */
    private function migrationCommands()
    {
        $this->registerModuleMigrator();
        $this->registerMigrateCommand();
    }

    private function registerMigrateCommand()
    {
        $commands = [
            'module_manager.console.command.module-migrate' => \App\Module\ModulesManagement\Console\Migrations\ModuleMigrateCommand::class
        ];
        foreach ($commands as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
        $this->registerRollbackCommand();

    }

    protected function otherCommands()
    {
        $this->commands([
            \App\Module\ModulesManagement\Console\Commands\InstallModuleCommand::class,
            \App\Module\ModulesManagement\Console\Commands\UpdateModuleCommand::class,
            \App\Module\ModulesManagement\Console\Commands\UninstallModuleCommand::class,
            \App\Module\ModulesManagement\Console\Commands\DisableModuleCommand::class,
            \App\Module\ModulesManagement\Console\Commands\EnableModuleCommand::class,
            // \App\Module\ModulesManagement\Console\Commands\ExportModuleCommand::class,
            \App\Module\ModulesManagement\Console\Commands\GetAllModulesCommand::class,
        ]);
    }
    
    /**
     * Register the "rollback" migration command.
     *
     * @return void
     */
    protected function registerRollbackCommand()
    {
        $this->app->singleton('module_manager.console.command.migration-rollback', function ($app) {
            return new \App\Module\ModulesManagement\Console\Migrations\RollbackCommand($app['module.migrator']);
        });
        $this->commands('module_manager.console.command.migration-rollback');
    }


    protected function registerModuleMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('module.migrator', function ($app) {
            return new ModuleMigrator($app);
        });
    }
}
