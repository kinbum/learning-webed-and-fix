<?php namespace App\Module\Acl\Repositories;

use App\Module\Acl\Repositories\Contracts\PermissionRepositoryContract;
use App\Module\Caching\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

class PermissionRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator implements PermissionRepositoryContract
{
    /**
     * Register permission
     * @param $name
     * @param $alias
     * @param $module
     * @param bool $withEvent
     * @param bool $force
     * @return array|\WebEd\Base\ACL\Repositories\PermissionRepository
     */
    public function registerPermission($name, $alias, $module, $withEvent = true, $force = true)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param string|array $alias
     * @param bool $withEvent
     * @param bool $force
     * @return array|\WebEd\Base\ACL\Repositories\PermissionRepository
     */
    public function unsetPermission($alias, $withEvent = true, $force = true)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param string|array $module
     * @param bool $withEvent
     * @param bool $force
     * @return array|\WebEd\Base\ACL\Repositories\PermissionRepository
     */
    public function unsetPermissionByModule($module, $withEvent = true, $force = true)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }
}