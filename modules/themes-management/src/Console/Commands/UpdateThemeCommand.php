<?php namespace App\Module\ThemesManagement\Console\Commands;

use Illuminate\Console\Command;

class UpdateThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:update {alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update WebEd theme';

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

        $this->line('Update module dependencies...');
        $this->registerUpdateModuleService($theme);

        $themeProvider = str_replace('\\\\', '\\', array_get($theme, 'namespace', '') . '\Providers\ModuleProvider');
        \Artisan::call('vendor:publish', [
            '--provider' => $themeProvider,
            '--tag' => 'webed-public-assets',
            '--force' => true
        ]);

        $this->info("\nTheme " . $this->argument('alias') . " has been updated.");
    }

    protected function registerUpdateModuleService($theme)
    {
        $namespace = str_replace('\\\\', '\\', array_get($theme, 'namespace', '') . '\Providers\UpdateModuleServiceProvider');
        if (class_exists($namespace)) {
            $this->app->register($namespace);
        }
        save_theme_information($theme, [
            'installed_version' => array_get($theme, 'version'),
        ]);
        $this->line('Updated');
    }
}
