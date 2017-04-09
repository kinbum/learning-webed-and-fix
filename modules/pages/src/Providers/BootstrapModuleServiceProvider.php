<?php namespace App\Module\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Pages\Repositories\Contracts\PageRepositoryContract;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Module\Pages';

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
            'id' => 'ace-pages',
            'priority' => 1,
            'parent_id' => null,
            'heading' => 'CORE',
            'title' => 'Pages',
            'font_icon' => 'icon-notebook',
            'link' => route('pages.index.get'),
            'css_class' => null,
            'permissions' => ['view-pages'],
        ]);

        menus_management()->registerWidget('Pages', 'page', function () {
            $repository = app(PageRepositoryContract::class)
                ->orderBy('created_at', 'DESC')
                ->get();
            $pages = [];
            foreach ($repository as $page) {
                $pages[] = [
                    'id' => $page->id,
                    'title' => $page->title,
                ];
            }
            return $pages;
        });

        $this->registerSettings();
    }
    private function registerSettings()
    {
        cms_settings()
            ->addSettingField('default_homepage', [
                'group' => 'basic',
                'type' => 'select',
                'priority' => 0,
                'label' => 'Default homepage',
                'helper' => null
            ], function () {
                /**
                 * @var PageRepository $pageRepo
                 */
                $pageRepo = app(PageRepositoryContract::class);

                $pages = $pageRepo->where('status', 'activated')
                    ->orderBy('order', 'ASC')
                    ->get();

                $pagesArr = [];

                foreach ($pages as $page) {
                    $pagesArr[$page->id] = $page->title;
                }

                return [
                    'default_homepage',
                    $pagesArr,
                    get_settings('default_homepage'),
                    ['class' => 'form-control']
                ];
            });
    }
}
