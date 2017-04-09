<?php namespace App\Plugins\CustomFields\Hook\Actions\Render;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Plugins\Blog\Models\Contracts\PostModelContract;

class Posts extends AbstractRenderer
{
    /**
     * @param string $type
     * @param PostModelContract $item
     */
    public function render($type, BaseModelContract $item)
    {
        if (!($type === 'blog.posts.create' || $type === 'blog.posts.edit')) {
            return;
        }

        $relatedCategories = $item->categories();
        $relatedCategoriesIds = $relatedCategories->allRelatedIds()->toArray();
        $relatedCategoryTemplates = $relatedCategories
            ->select('page_template')->get();
        $categoryTemplates = [];
        foreach ($relatedCategoryTemplates as $category) {
            $categoryTemplates[] = $category->page_template;
        }

        add_custom_field_rules([
            'blog.post_template' => isset($item->page_template) ? $item->page_template : '',
            'model_name' => 'blog.post',
            'blog.post_with_related_category' => $relatedCategoriesIds,
            'blog.post_with_related_category_template' => $categoryTemplates,
        ]);

        parent::render($type, $item);
    }
}
