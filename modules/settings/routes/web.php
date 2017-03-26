<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');
$moduleRoute = 'settings';

Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get('/', 'SettingController@index')
        ->name('settings.index.get')
        ->middleware('has-permission:view-settings');

    $router->post('', 'SettingController@store')
        ->name('settings.update.post')
        ->middleware('has-permission:edit-settings');
});