<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminPrefix = config('ace.admin_prefix');

/**
 * Admin routes
 */
Route::group(['prefix' => $adminPrefix], function (Router $router) use ($adminPrefix) {
    $router->group(['prefix' => 'themes-management'], function (Router $router) use ($adminPrefix) {
        $router->get('', 'ThemeController@getIndex')
            ->name('themes.index.get')
            ->middleware('has-permission:view-themes');
        $router->post('', 'ThemeController@postListing')
            ->name('themes.index.post')
            ->middleware('has-permission:view-themes');

        $router->post('change-status/{module}/{status}', 'ThemeController@postChangeStatus')
            ->name('themes.change-status.post')
            ->middleware('has-role:super-admin');

        $router->post('install/{module}', 'ThemeController@postInstall')
            ->name('themes.install.post')
            ->middleware('has-role:super-admin');

        $router->post('update/{module}', 'ThemeController@postUpdate')
            ->name('themes.update.post')
            ->middleware('has-role:super-admin');

        $router->post('uninstall/{module}', 'ThemeController@postUninstall')
            ->name('themes.uninstall.post')
            ->middleware('has-role:super-admin');
    });
    $router->group(['prefix' => 'theme-options'], function (Router $router) use ($adminPrefix) {
        $router->get('', 'ThemeOptionController@getIndex')
            ->name('theme-options.index.get')
            ->middleware('has-permission:view-theme-options');
        $router->post('', 'ThemeOptionController@postIndex')
            ->name('theme-options.index.post')
            ->middleware('has-role:update-theme-options');
    });
});
