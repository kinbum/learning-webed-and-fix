<?php namespace App\Plugins\CustomFields\Hook\Actions\Render;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Pages\Http\Controllers\PageController;

class Pages extends AbstractRenderer
{
    /**
     * @param BaseModelContract $item
     */
    /**
     * @param string $type
     * @param BaseModelContract $item
     */
    public function render($type, BaseModelContract $item)
    {
        if (!($type === 'pages.edit' || $type === 'pages.create')) {
            return;
        }

        add_custom_field_rules([
            'page_template' => isset($item->page_template) ? $item->page_template : '',
            'page' => isset($item->id) ? $item->id : '',
            'model_name' => 'page',
        ]);

        parent::render($type, $item);
    }
}
