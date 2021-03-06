<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Base';

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
         * Register dynamic menu or what you want when
         * bootstrap your module
         */
        $this->registerMenu();
        $this->generalSettings();
        $this->socialNetworks();
    }
    private function registerMenu()
    {
        /**
         * Register to dashboard menu
         */
        \DashboardMenu::registerItem([
            'id' => 'ace-dashboard',
            'priority' => -999,
            'parent_id' => null,
            'heading' => 'Dashboard',
            'title' => 'Dashboard',
            'font_icon' => 'icon-pie-chart',
            'link' => route('dashboard.index.get'),
            'css_class' => null,
        ]);
        
        \DashboardMenu::registerItem([
            'id' => 'ace-configuration',
            'priority' => 999,
            'parent_id' => null,
            'heading' => 'Advanced',
            'title' => 'Configurations',
            'font_icon' => 'icon-settings',
            'link' => route('settings.index.get'),
            'css_class' => null,
        ]);

    }

    private function generalSettings()
    {
        cms_settings()
            ->addSettingField('site_title', [
                'group' => 'basic',
                'type' => 'text',
                'priority' => 5,
                'label' => 'Site title',
                'helper' => 'Our site title'
            ], function () {
                return [
                    'site_title',
                    get_settings('site_title'),
                    ['class' => 'form-control']
                ];
            })
            ->addSettingField('site_logo', [
                'group' => 'basic',
                'type' => 'selectImageBox',
                'priority' => 5,
                'label' => 'Site logo',
                'helper' => 'Our site logo'
            ], function () {
                return [
                    'site_logo',
                    get_settings('site_logo'),
                ];
            })
            ->addSettingField('favicon', [
                'group' => 'basic',
                'type' => 'selectImageBox',
                'priority' => 5,
                'label' => 'Favicon',
                'helper' => '16x16, support png, gif, ico, jpg'
            ], function () {
                return [
                    'favicon',
                    get_settings('favicon'),
                ];
            })
            ->addSettingField('construction_mode', [
                'group' => 'advanced',
                'type' => 'customCheckbox',
                'priority' => 5,
                'label' => null,
                'helper' => 'Mark this site on maintenance mode. Just logged in admin can access front site.',
            ], function () {
                return [
                    [['construction_mode', '1', 'On construction mode', get_settings('construction_mode'),]],
                ];
            })
            ->addSettingField('show_admin_bar', [
                'group' => 'advanced',
                'type' => 'customCheckbox',
                'priority' => 5,
                'label' => null,
                'helper' => 'When admin logged in, still show admin bar on front site.'
            ], function () {
                return [
                    [['show_admin_bar', '1', 'Show admin bar', get_settings('show_admin_bar')]],
                ];
            });
    }

    private function socialNetworks()
    {
        cms_settings()->addGroup('socials', 'Social networks');

        $socials = [
            'facebook' => [
                'label' => 'Facebook page',
            ],
            'youtube' => [
                'label' => 'Youtube chanel',
            ],
            'twitter' => [
                'label' => 'Twitter page',
            ]
        ];
        foreach ($socials as $key => $row) {
            cms_settings()->addSettingField($key, [
                'group' => 'socials',
                'type' => 'text',
                'priority' => 1,
                'label' => $row['label'],
                'helper' => null
            ], function () use ($key) {
                return [
                    $key,
                    get_settings($key),
                    [
                        'class' => 'form-control',
                        'placeholder' => 'https://',
                        'autocomplete' => 'off'
                    ]
                ];
            });
        }
    }
}
