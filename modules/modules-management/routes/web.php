<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');

$moduleRoute = 'modules-management';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('', function () {
        return redirect(route('core-modules.index.get'));
    });
    $router->get('plugins', 'PluginsController@getIndex')
        ->name('plugins.index.get')
        ->middleware('has-permission:view-plugins');

    $router->post('plugins', 'PluginsController@postListing')
        ->name('plugins.index.post')
        ->middleware('has-permission:view-plugins');

    $router->post('plugins/change-status/{module}/{status}', 'PluginsController@postChangeStatus')
        ->name('plugins.change-status.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/install/{module}', 'PluginsController@postInstall')
        ->name('plugins.install.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/update/{module}', 'PluginsController@postUpdate')
        ->name('plugins.update.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/uninstall/{module}', 'PluginsController@postUninstall')
        ->name('plugins.uninstall.post')
        ->middleware('has-role:super-admin');
});
