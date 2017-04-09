<?php namespace App\Module\ThemesManagement\Console\Commands;

use Illuminate\Console\Command;

class EnableThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:enable {alias : Theme alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable theme';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

        themes_management()->enableTheme($this->argument('alias'))->refreshComposerAutoload();

        $this->info("Your theme enabled successfully.");
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
}
