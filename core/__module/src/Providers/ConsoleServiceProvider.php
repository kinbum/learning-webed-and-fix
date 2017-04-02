<?php namespace Core\Module\Providers;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;
use Core\Module\Services\ModuleMigrator;

/**
 * Class ConsoleServiceProvider
 * @package Core\Module\Providers
 */
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
        $this->migrationCommands();
        $this->otherCommands();
    }

    /**
     * register generator commands
     */
    private function generatorCommands()
    {
        $generators = [
            'module_manager.console.generator.make-module' => \Core\Module\Console\Generators\MakeModule::class,
            'module_manager.console.generator.make-provider' => \Core\Module\Console\Generators\MakeProvider::class,
            'module_manager.console.generator.make-controller' => \Core\Module\Console\Generators\MakeController::class,
            'module_manager.console.generator.make-middleware' => \Core\Module\Console\Generators\MakeMiddleware::class,
            'module_manager.console.generator.make-request' => \Core\Module\Console\Generators\MakeRequest::class,
            'module_manager.console.generator.make-model' => \Core\Module\Console\Generators\MakeModel::class,
            // 'module_manager.console.generator.make-repository' => \Core\Module\Console\Generators\MakeRepository::class,
            'module_manager.console.generator.make-facade' => \Core\Module\Console\Generators\MakeFacade::class,
            'module_manager.console.generator.make-service' => \Core\Module\Console\Generators\MakeService::class,
            'module_manager.console.generator.make-support' => \Core\Module\Console\Generators\MakeSupport::class,
//            'module_manager.console.generator.make-view' => \Core\Module\Console\Generators\MakeView::class,
            'module_manager.console.generator.make-migration' => \Core\Module\Console\Generators\MakeMigration::class,
            'module_manager.console.generator.make-command' => \Core\Module\Console\Generators\MakeCommand::class,
        ];
        foreach ($generators as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
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
            'module_manager.console.command.module-migrate' => \Core\Module\Console\Migrations\ModuleMigrateCommand::class
        ];
        foreach ($commands as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
        $this->registerRollbackCommand();

    }
    private function otherCommands()
    {
        $commands = [
            'module_manager.console.command.module-list' => \Core\Module\Console\Commands\GetAllModulesCommand::class,
            'module_manager.console.command.module-install' => \Core\Module\Console\Commands\InstallModuleCommand::class,
            'module_manager.console.command.module-uninstall' => \Core\Module\Console\Commands\UninstallModuleCommand::class,
            'module_manager.console.command.disable-module' => \Core\Module\Console\Commands\DisableModuleCommand::class,
            'module_manager.console.command.enable-module' => \Core\Module\Console\Commands\EnableModuleCommand::class,
            'module_manager.console.command.module-route-list' => \Core\Module\Console\Commands\RouteListCommand::class,
        ];
        foreach ($commands as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
    }
    /**
     * Register the "rollback" migration command.
     *
     * @return void
     */
    protected function registerRollbackCommand()
    {
        $this->app->singleton('module_manager.console.command.migration-rollback', function ($app) {
            return new \Core\Module\Console\Migrations\RollbackCommand($app['module.migrator']);
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
