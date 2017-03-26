<?php namespace App\Module\Acl\Repositories\Contracts;

interface PermissionRepositoryContract {
    /**
     * Register permission
     * @param $name
     * @param $alias
     * @param $module
     * @param bool $force
     * @return array|PermissionRepository
     */
    public function registerPermission ( $name, $alias, $module, $force = true );

    /**
     * @param string|array $alias
     * @param bool $force
     * @return array|\PermissionRepository
     */
    public function unsetPermission ( $alias, $force = true );

    /**
     * @param string|array $module
     * @param bool $force
     * @return array|PermissionRepository
     */
    public function unsetPermissionByModule($module, $force = true);
}