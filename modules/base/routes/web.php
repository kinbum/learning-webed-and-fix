<?php
use Illuminate\Routing\Router;

$adminPrefix = config('ace.admin_prefix');

Route::group([ 'prefix' => $adminPrefix ], function (Router $route) use ($adminPrefix) {
    $route->get('/', 'DashboardController@index')
        ->name('dashboard.index.get')
        ->middleware('has-permission:access-dashboard');
});
