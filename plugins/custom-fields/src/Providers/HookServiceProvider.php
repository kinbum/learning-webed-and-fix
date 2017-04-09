<?php namespace App\Plugins\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot()
    {
        assets_management()->addJavascripts(['jquery-ckeditor']);

        add_action(
            'meta_boxes',
            [\App\Plugins\CustomFields\Hook\Actions\Render\MappingActionsByType::class, 'handle']
        );

        add_action(
            'pages.after-edit.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Pages::class, 'afterSaveContent']
        );

        add_action(
            'pages.after-create.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Pages::class, 'afterCreate']
        );

        add_action(
            'footer_js',
            [\App\Plugins\CustomFields\Hook\Actions\AssetsInjection::class, 'renderJs']
        );

        /**
         * Register blog actions
         */
        add_action(
            'blog.posts.after-edit.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Posts::class, 'afterSaveContent']
        );
        add_action(
            'blog.posts.after-create.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Posts::class, 'afterSaveContent']
        );
        add_action(
            'blog.categories.after-edit.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Categories::class, 'afterSaveContent']
        );
        add_action(
            'blog.categories.after-create.post',
            [\App\Plugins\CustomFields\Hook\Actions\Store\Categories::class, 'afterSaveContent']
        );
    }
}
