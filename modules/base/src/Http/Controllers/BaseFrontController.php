<?php namespace App\Module\Base\Http\Controllers;

use Illuminate\Http\Request;

class BaseFrontController extends BaseController
{
    /**
     * @var mixed|Controller|BaseController
     */
    protected $themeController;

    /**
     * @var string
     */
    protected $currentThemeName = 'theme';

    public function __construct() {
    }

    /**
     * @param $type
     * @param $relatedId
     * @return null|string|mixed
     */
    protected function getMenu($type, $relatedId)
    {
        $menuHtml = webed_menu_render(get_settings('top_menu', 'top-menu'), [
            'class' => 'nav navbar-nav navbar-right',
            'container_class' => 'collapse navbar-collapse',
            'has_sub_class' => 'dropdown',
            'menu_active' => [
                'type' => $type,
                'related_id' => $relatedId,
            ]
        ]);
        return $menuHtml;
    }
}
