<?php namespace App\Module\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Menu\Repositories\Contracts\MenuRepositoryContract;
use App\Module\Menu\Repositories\MenuRepository;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Menu';

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
        \DashboardMenu::registerItem([
            'id' => 'ace-menu',
            'priority' => 20,
            'parent_id' => null,
            'heading' => null,
            'title' => 'Menus',
            'font_icon' => 'fa fa-bars',
            'link' => route('menus.index.get'),
            'css_class' => null,
            'permissions' => ['view-menus']
        ]);

        cms_settings()
            ->addSettingField('main_menu', [
                'group' => 'basic',
                'type' => 'select',
                'priority' => 3,
                'label' => 'Main menu',
                'helper' => 'Main menu of our website'
            ], function () {
                /**
                 * @var MenuRepository $menus
                 */
                $menus = app(MenuRepositoryContract::class);
                $menus = $menus->where('status', '=', 'activated')
                    ->get();

                $menusArr = [];

                foreach ($menus as $menu) {
                    $menusArr[$menu->slug] = $menu->title;
                }

                return [
                    'main_menu',
                    $menusArr,
                    get_settings('main_menu'),
                    ['class' => 'form-control']
                ];
            });
    }
}
