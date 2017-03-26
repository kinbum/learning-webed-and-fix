<?php
use App\Module\Acl\Repositories\Contracts\PermissionRepositoryContract;
use App\Module\Acl\Facades\CheckUserACLFacade;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;

if (!function_exists('check_user_acl')) {
    /**
     * @return CheckUserACL
     */
    function check_user_acl()
    {
        return CheckUserACLFacade::getFacadeRoot();
    }
}

if (!function_exists('acl_permission')) {
    /**
     * Get the PermissionRepository instance.
     *
     * @return PermissionRepository
     */
    function acl_permission()
    {
        return app(PermissionRepositoryContract::class);
    }
}

if (!function_exists('has_permissions')) {
    /**
     * @param Module\Users\Models\User $user
     * @param array $permissions
     * @return bool
     */
    function has_permissions($user, array $permissions = [])
    {
        if (!$user) {
            return false;
        }

        if (!$permissions) {
            return true;
        }

        /**
         * @var \UserRepository $userRepo
         */
        $userRepo = app(UserRepositoryContract::class);
        return $userRepo->hasPermission($user, $permissions);
    }
}

if (!function_exists('has_roles')) {
    /**
     * @param Module\Users\Models\User $user
     * @param array $roles
     * @return bool
     */
    function has_roles($user, $roles = [])
    {
        if (!$user) {
            return false;
        }

        if (!$roles) {
            return true;
        }

        $userRepo = app(UserRepositoryContract::class);
        return $userRepo->hasRole($user, $roles);
    }
}