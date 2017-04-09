<?php namespace App\Plugins\Blog\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\Blog';

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
            'id' => 'ace-blog-posts',
            'priority' => 2,
            'parent_id' => null,
            'heading' => 'Blog',
            'title' => 'Posts',
            'font_icon' => 'icon-book-open',
            'link' => route('blog.posts.index.get'),
            'css_class' => null,
            'permissions' => ['view-posts'],
        ])->registerItem([
            'id' => 'ace-blog-categories',
            'priority' => 2.1,
            'parent_id' => null,
            'title' => 'Categories',
            'font_icon' => 'fa fa-sitemap',
            'link' => route('blog.categories.index.get'),
            'css_class' => null,
            'permissions' => ['view-categories'],
        ])->registerItem([
            'id' => 'ace-blog-tags',
            'priority' => 2.2,
            'parent_id' => null,
            'title' => 'Tags',
            'font_icon' => 'icon-tag',
            'link' => route('blog.tags.index.get'),
            'css_class' => null,
            'permissions' => ['view-tags'],
        ]);


        menus_management()->registerWidget('Categories', 'category', function () {
            $categories = get_categories_with_children();
            return $this->parseMenuWidgetData($categories);
        });

        /**
         * Register menu link type
         */
        // menus_management()->registerLinkType('category', function ($id) {
        //     $category = app(CategoryRepositoryContract::class)
        //         ->where('id', '=', $id)
        //         ->first();
        //     if (!$category) {
        //         return null;
        //     }
        //     return [
        //         'model_title' => $category->title,
        //         'url' => route('front.web.resolve-blog.get', ['slug' => $category->slug]),
        //     ];
        // });

    }

    private function parseMenuWidgetData($categories)
    {
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'title' => $category->title,
                'children' => $this->parseMenuWidgetData($category->child_cats)
            ];
        }
        return $result;
    }
}
