<?php namespace App\Module\ThemesManagement\Console\Commands;

use Illuminate\Console\Command;

class InstallThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:install {alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install WebEd theme';

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
        $theme = get_theme_information($this->argument('alias'));
        if (!$theme) {
            $this->error('Theme not exists');
            die();
        }

        $this->detectRequiredDependencies($theme);

        $this->line('Install module dependencies...');
        $this->registerInstallModuleService($theme);

        $this->info("\nTheme " . $this->argument('alias') . " installed.");
    }

    protected function detectRequiredDependencies($theme)
    {
        $checkRelatedModules = check_module_require($theme);
        if ($checkRelatedModules['error']) {
            foreach ($checkRelatedModules['messages'] as $message) {
                $this->error($message);
            }
            die();
        }
    }

    protected function registerInstallModuleService($theme)
    {
        $namespace = str_replace('\\\\', '\\', array_get($theme, 'namespace', '') . '\Providers\InstallModuleServiceProvider');
        if (class_exists($namespace)) {
            $this->app->register($namespace);
        }
        /**
         * Publish theme assets
         */
        \Artisan::call('vendor:publish', [
            '--provider' => str_replace('\\\\', '\\', array_get($theme, 'namespace', '') . '\Providers\ModuleProvider'),
            '--tag' => 'webed-public-assets',
            '--force' => true
        ]);
        save_theme_information($theme, [
            'installed' => true,
            'installed_version' => array_get($theme, 'version'),
        ]);
        $this->line('Installed');
    }
}
