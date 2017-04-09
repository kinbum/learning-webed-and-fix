<?php namespace App\Plugins\CustomFields\Hook\Actions\Store;

class Categories extends AbstractStore
{
    /**
     * @var string
     */
    protected $repositoryInterface = 'App\Plugins\Blog\Repositories\Contracts\CategoryRepositoryContract';
}
