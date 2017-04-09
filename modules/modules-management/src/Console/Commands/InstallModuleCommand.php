<?php namespace App\Module\ModulesManagement\Console\Commands;

use Illuminate\Console\Command;

class InstallModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install module';

    /**
     * @var array
     */
    protected $container = [];

    /**
     * @var array
     */
    protected $dbInfo = [];

    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $app;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->app = app();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = get_module_information($this->argument('alias'));
        if (!$module) {
            $this->error('Module not exists');
            die();
        }

        if (array_get($module, 'installed') === true) {
            $this->info("\nModule " . $this->argument('alias') . " installed.");
            return;
        }

        $this->detectRequiredDependencies($module);
        $this->registerInstallModuleService($module);


        $this->info("\nModule " . $this->argument('alias') . " installed.");
    }

    protected function registerInstallModuleService($module)
    {
        /**
         * Migrate tables
         */
        \ModulesManagement::enableModule($this->argument('alias'));
        \ModulesManagement::modifyModuleAutoload($this->argument('alias'));

        $this->line('Migrate database...');
        \Artisan::call('module:migrate', ['alias' => $this->argument('alias')]);
        $this->line('Install module dependencies...');

        $module = get_module_information($this->argument('alias'));
        $namespace = str_replace('\\\\', '\\', array_get($module, 'namespace', '') . '\Providers\InstallModuleServiceProvider');
        if(class_exists($namespace)) {
            $this->app->register($namespace);
        }
        save_module_information($module, [
            'installed' => true
        ]);

    }

    protected function detectRequiredDependencies($module)
    {
        $checkRelatedModules = check_module_require($module);
        if ($checkRelatedModules['error']) {
            foreach ($checkRelatedModules['messages'] as $message) {
                $this->error($message);
            }
            die();
        }
    }
}
