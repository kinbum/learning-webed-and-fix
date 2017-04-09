<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$adminRoute = 'files';

/**
 * Admin routes
 */
Route::group([
    'prefix' => $adminPrefix . '/' . $adminRoute,
    // 'middleware' => 'has-permission:view-files,crud-files'
], function (Router $router) use ($adminRoute, $adminPrefix) {
    $router->get('', 'ElfinderController@getIndex')
        ->name('elfinder.index.get')
        ->middleware('has-permission:view-files');

    $router->get('/stand-alone', 'ElfinderController@getStandAlone')
        ->name('elfinder.stand-alone.get')
        ->middleware('has-permission:view-files');

    $router->get('/elfinder-view', 'ElfinderController@getElfinderView')
        ->name('elfinder.popup.get')
        ->middleware('has-permission:view-files');

    $router->any('/connector', 'ElfinderController@anyConnector')
        ->name('elfinder.connect')
        ->middleware('has-permission:view-files');
});
