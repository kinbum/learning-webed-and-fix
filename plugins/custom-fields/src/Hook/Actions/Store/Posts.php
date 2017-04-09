<?php namespace App\Plugins\CustomFields\Hook\Actions\Store;

class Posts extends AbstractStore
{
    /**
     * @var string
     */
    protected $repositoryInterface = 'App\Plugins\Blog\Repositories\Contracts\PostRepositoryContract';
}
