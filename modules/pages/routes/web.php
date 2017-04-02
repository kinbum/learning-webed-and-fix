<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$moduleRoute = 'pages';

Route::group([ 'prefix' => $adminPrefix . '/' . $moduleRoute ], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('/', 'PageController@getIndex')
        ->name('pages.index.get')
        ->middleware('has-permission:view-pages');

    $router->post('/', 'PageController@postListing')
        ->name('pages.index.post')
        ->middleware('has-permission:view-pages');

    $router->post('update-status/{id}/{status}', 'PageController@postUpdateStatus')
        ->name('pages.update-status.post')
        ->middleware('has-permission:edit-pages');

    $router->get('create', 'PageController@getCreate')
        ->name('pages.create.get')
        ->middleware('has-permission:create-pages');

    $router->post('create', 'PageController@postCreate')
        ->name('pages.create.post')
        ->middleware('has-permission:create-pages');

    $router->get('edit/{id}', 'PageController@getEdit')
        ->name('pages.edit.get')
        ->middleware('has-permission:view-pages');

    $router->post('edit/{id}', 'PageController@postEdit')
        ->name('pages.edit.post')
        ->middleware('has-permission:edit-pages');

    $router->delete('/{id}', 'PageController@deleteDelete')
        ->name('pages.delete.delete')
        ->middleware('has-permission:delete-pages');
});
