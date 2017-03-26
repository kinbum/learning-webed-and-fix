<?php namespace App\Module\Menu\Repositories;

use App\Module\Caching\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

use App\Module\Menu\Models\Contracts\MenuModelContract;
use App\Module\Menu\Repositories\Contracts\MenuRepositoryContract;

class MenuRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator  implements MenuRepositoryContract
{
    /**
     * Create menu
     * @param $data
     * @return array
     */
    public function createMenu($data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * Update menu
     * @param $id
     * @param $data
     * @param bool $allowCreateNew
     * @param bool $justUpdateSomeFields
     * @return array
     */
    public function updateMenu($id, $data, $allowCreateNew = false, $justUpdateSomeFields = true)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * Update menu structure
     * @param $menuId
     * @param $menuStructure
     */
    public function updateMenuStructure($menuId, $menuStructure)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * Get menu
     * @param $id
     * @return mixed|null|MenuModelContract
     */
    public function getMenu($id)
    {
        return $this->beforeGet(__FUNCTION__, func_get_args());
    }
}
