<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminRoute = config('ace.admin_prefix');

$moduleRoute = 'users';

/*
 * Admin route
 * */
Route::group(['prefix' => $adminRoute . '/' . $moduleRoute], function (Router $router) use ($adminRoute, $moduleRoute) {
    $router->get('/', 'UserController@getIndex')
        ->name('users.index.get')
        ->middleware('has-permission:view-users');

    $router->post('/', 'UserController@postListing')
        ->name('users.index.post')
        ->middleware('has-permission:view-users');

    $router->post('update-status/{id}/{status}', 'UserController@postUpdateStatus')
        ->name('users.update-status.post')
        ->middleware('has-permission:edit-other-users');

    $router->post('restore/{id}', 'UserController@postRestore')
        ->name('users.restore.post')
        ->middleware('has-permission:edit-other-users');

    $router->get('create', 'UserController@getCreate')
        ->name('users.create.get')
        ->middleware('has-permission:create-users');

    $router->post('create', 'UserController@postCreate')
        ->name('users.create.post')
        ->middleware('has-permission:create-users');

    $router->get('edit/{id}', 'UserController@getEdit')
        ->name('users.edit.get');
        
    $router->post('edit/{id}', 'UserController@postEdit')
        ->name('users.edit.post');

    $router->post('update-password/{id}', 'UserController@postUpdatePassword')
        ->name('users.update-password.post');

    $router->delete('delete/{id}', 'UserController@deleteDelete')
        ->name('users.delete.delete')
        ->middleware('has-permission:delete-users');

    $router->delete('force-delete/{id}', 'UserController@deleteForceDelete')
        ->name('users.force-delete.delete')
        ->middleware('has-permission:force-delete-users');
});
