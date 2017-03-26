<?php
use Illuminate\Routing\Router;

$adminRoute = config('ace.admin_route');

$moduleRoute = 'caching';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminRoute . '/' . $moduleRoute], function (Router $router) use ($adminRoute, $moduleRoute) {
    $router->get('', 'CachingController@getIndex')
        ->name('caching.index.get');

    $router->get('clear-cms-cache', 'CachingController@getClearCmsCache')
        ->name('caching.clear-cms-cache.get');

    $router->get('refresh-compiled-views', 'CachingController@getRefreshCompiledViews')
        ->name('caching.refresh-compiled-views.get');

    $router->get('create-config-cache', 'CachingController@getCreateConfigCache')
        ->name('caching.create-config-cache.get');

    $router->get('clear-config-cache', 'CachingController@getClearConfigCache')
        ->name('caching.clear-config-cache.get');

    $router->get('optimize-class', 'CachingController@getOptimizeClass')
        ->name('caching.optimize-class.get');

    $router->get('clear-compiled-class', 'CachingController@getClearCompiledClass')
        ->name('caching.clear-compiled-class.get')
        ->middleware('has-permission:clear-cache');

    $router->get('create-route-cache', 'CachingController@getCreateRouteCache')
        ->name('caching.create-route-cache.get');

    $router->get('clear-route-cache', 'CachingController@getClearRouteCache')
        ->name('caching.clear-route-cache.get');
});
