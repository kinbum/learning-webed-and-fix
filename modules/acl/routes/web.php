<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$moduleRoute = 'acl';

Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('', function () {
        return redirect()->to(route('acl-roles.index.get'));
    });
    /**
     * Roles
     */
    $router->group(['prefix' => 'roles'], function (Router $router) {
        $router->get('', 'RoleController@getIndex')
            ->name('acl-roles.index.get')
            ->middleware('has-permission:view-roles');

        $router->post('', 'RoleController@postListing')
            ->name('acl-roles.index.get-json')
            ->middleware('has-permission:view-roles');

        $router->get('create', 'RoleController@getCreate')
            ->name('acl-roles.create.get')
            ->middleware('has-permission:create-roles');

        $router->post('create', 'RoleController@postCreate')
            ->name('acl-roles.create.post')
            ->middleware('has-permission:create-roles');

        $router->get('edit/{id}', 'RoleController@getEdit')
            ->name('acl-roles.edit.get')
            ->middleware('has-permission:view-roles');

        $router->post('edit/{id}', 'RoleController@postEdit')
            ->name('acl-roles.edit.post')
            ->middleware('has-permission:edit-roles');

        $router->delete('{id}', 'RoleController@deleteDelete')
            ->name('acl-roles.delete.delete')
            ->middleware('has-permission:delete-roles');
    });


    /**
     * Permissions
     */
    $router->group(['prefix' => 'permissions'], function (Router $router) {
        $router->get('', 'PermissionController@getIndex')
            ->name('acl-permissions.index.get')
            ->middleware('has-permission:view-permissions');

        $router->post('', 'PermissionController@postListing')
            ->name('acl-permissions.index.post')
            ->middleware('has-permission:view-permissions');
    });
});
