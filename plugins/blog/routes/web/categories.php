<?php
use Illuminate\Routing\Router;

/**
 *
 * @var Router $router
 *
 */

$router->group(['prefix' => 'categories'], function (Router $router) {
    $router->get('', 'CategoryController@getIndex')
        ->name('blog.categories.index.get')
        ->middleware('has-permission:view-categories');

    $router->post('', 'CategoryController@postListing')
        ->name('blog.categories.index.post')
        ->middleware('has-permission:view-categories');

    $router->get('create', 'CategoryController@getCreate')
        ->name('blog.categories.create.get')
        ->middleware('has-permission:create-categories');

    $router->post('create', 'CategoryController@postCreate')
        ->name('blog.categories.create.post')
        ->middleware('has-permission:create-categories');

    $router->get('edit/{id}', 'CategoryController@getEdit')
        ->name('blog.categories.edit.get')
        ->middleware('has-permission:view-categories');

    $router->post('edit/{id}', 'CategoryController@postEdit')
        ->name('blog.categories.edit.post')
        ->middleware('has-permission:edit-categories');

    $router->post('update-status/{id}/{status}', 'CategoryController@postUpdateStatus')
        ->name('blog.categories.update-status.post')
        ->middleware('has-permission:edit-categories');

    $router->delete('{id}', 'CategoryController@deleteDelete')
        ->name('blog.categories.delete.delete')
        ->middleware('has-permission:delete-categories');
});