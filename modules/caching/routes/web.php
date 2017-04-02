<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$moduleRoute = 'caching';

Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('', 'CachingController@getIndex')
        ->name('caching.index.get')
        ->middleware('has-permission:view-cache');

    $router->get('clear-cms-cache', 'CachingController@getClearCmsCache')
        ->name('caching.clear-cms-cache.get')
        ->middleware('has-permission:clear-cache');

    $router->get('refresh-compiled-views', 'CachingController@getRefreshCompiledViews')
        ->name('caching.refresh-compiled-views.get')
        ->middleware('has-permission:clear-cache');

    $router->get('create-config-cache', 'CachingController@getCreateConfigCache')
        ->name('caching.create-config-cache.get')
        ->middleware('has-permission:modify-cache');

    $router->get('clear-config-cache', 'CachingController@getClearConfigCache')
        ->name('caching.clear-config-cache.get')
        ->middleware('has-permission:clear-cache');

    $router->get('optimize-class', 'CachingController@getOptimizeClass')
        ->name('caching.optimize-class.get')
        ->middleware('has-permission:modify-cache');

    $router->get('clear-compiled-class', 'CachingController@getClearCompiledClass')
        ->name('caching.clear-compiled-class.get')
        ->middleware('has-permission:clear-cache');

    $router->get('create-route-cache', 'CachingController@getCreateRouteCache')
        ->name('caching.create-route-cache.get')
        ->middleware('has-permission:modify-cache');

    $router->get('clear-route-cache', 'CachingController@getClearRouteCache')
        ->name('caching.clear-route-cache.get')
        ->middleware('has-permission:clear-cache');
});
