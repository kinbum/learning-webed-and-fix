<?php namespace App\Module\Menu\Repositories;

use App\Module\Caching\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

use App\Module\Menu\Repositories\Contracts\MenuNodeRepositoryContract;

class MenuNodeRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator  implements MenuNodeRepositoryContract
{
    /**
     * $messages
     * @param $menuId
     * @param $node
     * @param null $parentId
     */
    public function updateMenuNode($menuId, $node, $order, $parentId = null)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args(), true, true);
    }

    /**
     * Get menu nodes
     * @param $menuId
     * @param null|int $parentId
     * @return mixed|null
     */
    public function getMenuNodes($menuId, $parentId = null)
    {
        return $this->beforeGet(__FUNCTION__, func_get_args());
    }
}
