<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$moduleRoute = 'menus';

Route::group([ 'prefix' => $adminPrefix . '/' . $moduleRoute ], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('', 'MenuController@getIndex')
        ->name('menus.index.get')
        ->middleware('has-permission:view-menus');

    $router->post('', 'MenuController@postListing')
        ->name('menus.index.post')
        ->middleware('has-permission:view-menus');

    $router->post('update-status/{id}/{status}', 'MenuController@postUpdateStatus')
        ->name('menus.update-status.post')
        ->middleware('has-permission:edit-menus');

    $router->get('create', 'MenuController@getCreate')
        ->name('menus.create.get')
        ->middleware('has-permission:create-menus');

    $router->post('create', 'MenuController@postCreate')
        ->name('menus.create.post')
        ->middleware('has-permission:create-menus');

    $router->get('edit/{id}', 'MenuController@getEdit')
        ->name('menus.edit.get')
        ->middleware('has-permission:view-menus');

    $router->post('edit/{id}', 'MenuController@postEdit')
        ->name('menus.edit.post')
        ->middleware('has-permission:edit-menus');

    $router->delete('/{id}', 'MenuController@deleteDelete')
        ->name('menus.delete.delete')
        ->middleware('has-permission:delete-menus');
});
