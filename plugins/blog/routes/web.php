<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminPrefix = config('ace.admin_prefix');

$moduleRoute = 'blog';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminPrefix . '/' . $moduleRoute], function (Router $router) {
    require 'web/posts.php';
    require 'web/categories.php';
    require 'web/tags.php';
});

/**
 * Front site routes
 */
Route::get('blogs/{slug}.html', function () {
  dd('blogs/{slug}.html');
})->name('front.web.resolve-blog.get');

Route::get('blogs/tag/{slug}.html', function () {
  dd('blogs/tag/{slug}.html');
})->name('front.web.blog.tags.get');
