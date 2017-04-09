<?php
use Illuminate\Routing\Router;

/**
 *
 * @var Router $router
 *
 */

$router->group(['prefix' => 'tags'], function (Router $router) {
    $router->get('', 'BlogTagController@getIndex')
        ->name('blog.tags.index.get')
        ->middleware('has-permission:view-tags');

    $router->post('', 'BlogTagController@postListing')
        ->name('blog.tags.index.post')
        ->middleware('has-permission:view-tags');

    $router->get('create', 'BlogTagController@getCreate')
        ->name('blog.tags.create.get')
        ->middleware('has-permission:create-tags');

    $router->post('create', 'BlogTagController@postCreate')
        ->name('blog.tags.create.post')
        ->middleware('has-permission:create-tags');

    $router->get('edit/{id}', 'BlogTagController@getEdit')
        ->name('blog.tags.edit.get')
        ->middleware('has-permission:view-tags');

    $router->post('edit/{id}', 'BlogTagController@postEdit')
        ->name('blog.tags.edit.post')
        ->middleware('has-permission:edit-tags');

    $router->post('update-status/{id}/{status}', 'BlogTagController@postUpdateStatus')
        ->name('blog.tags.update-status.post')
        ->middleware('has-permission:edit-tags');

    $router->delete('{id}', 'BlogTagController@deleteDelete')
        ->name('blog.tags.delete.delete')
        ->middleware('has-permission:delete-tags');
});