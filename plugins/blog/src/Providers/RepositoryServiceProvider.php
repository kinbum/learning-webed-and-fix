<?php namespace App\Plugins\Blog\Providers;

use Illuminate\Support\ServiceProvider;

use App\Plugins\Blog\Models\BlogTag;
use App\Plugins\Blog\Models\Category;
use App\Plugins\Blog\Models\Post;
use App\Plugins\Blog\Repositories\BlogTagRepository;
use App\Plugins\Blog\Repositories\CategoryRepository;
use App\Plugins\Blog\Repositories\Contracts\BlogTagRepositoryContract;
use App\Plugins\Blog\Repositories\Contracts\CategoryRepositoryContract;
use App\Plugins\Blog\Repositories\Contracts\PostRepositoryContract;
use App\Plugins\Blog\Repositories\PostRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\Blog';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(PostRepositoryContract::class, function () {
            $repository = new PostRepository(new Post());
            return $repository;
        });

        $this->app->bind(CategoryRepositoryContract::class, function () {
            $repository = new CategoryRepository(new Category());
            return $repository;
        });

        $this->app->bind(BlogTagRepositoryContract::class, function () {
            $repository = new BlogTagRepository(new BlogTag());
            return $repository;
        });
    }
}
