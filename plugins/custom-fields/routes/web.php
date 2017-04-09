<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminPrefix = config('ace.admin_prefix');

$moduleRoute = 'custom-fields';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) {
     $router->get('/', 'CustomFieldController@getIndex')
        ->name('custom-fields.index.get')
        ->middleware('has-permission:view-custom-fields');

    $router->post('/', 'CustomFieldController@postListing')
        ->name('custom-fields.index.post')
        ->middleware('has-permission:view-custom-fields');

    $router->post('/update-status/{id}/{status}', 'CustomFieldController@postUpdateStatus')
        ->name('custom-fields.field-group.update-status.post')
        ->middleware('has-permission:edit-field-groups');

    $router->get('/create', 'CustomFieldController@getCreate')
        ->name('custom-fields.field-group.create.get')
        ->middleware('has-permission:create-field-groups');

    $router->post('/create', 'CustomFieldController@postCreate')
        ->name('custom-fields.field-group.create.post')
        ->middleware('has-permission:create-field-groups');

    $router->get('/edit/{id}', 'CustomFieldController@getEdit')
        ->name('custom-fields.field-group.edit.get')
        ->middleware('has-permission:edit-field-groups');

    $router->post('/edit/{id}', 'CustomFieldController@postEdit')
        ->name('custom-fields.field-group.edit.post')
        ->middleware('has-permission:edit-field-groups');

    $router->delete('/delete/{id}', 'CustomFieldController@deleteDelete')
        ->name('custom-fields.field-group.delete')
        ->middleware('has-permission:delete-field-groups');
});