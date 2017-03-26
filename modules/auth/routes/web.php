<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminPrefix = config('ace.admin_prefix');

$moduleRoute = 'auth';

/*
 * Admin route
 * */
Route::group(['prefix' => $adminPrefix], function (Router $router) use ($adminPrefix, $moduleRoute) {
    $router->get($moduleRoute, function () use ($adminPrefix, $moduleRoute) {
        return redirect()->to($adminPrefix . '/' . $moduleRoute . '/login');
    });

    $router->group(['prefix' => $moduleRoute], function (Router $router) use ($adminPrefix, $moduleRoute) {
        $router->get('login', 'AuthController@getLogin')->name('auth.login.get');
        $router->post('login', 'AuthController@postLogin')->name('auth.login.post');
        $router->get('logout', 'AuthController@getLogout')->name('auth.logout.get');
    });
});
