<?php namespace App\Plugins\Blog\Repositories\Contracts;

use App\Plugins\Blog\Models\Contracts\PostModelContract;
use App\Plugins\Blog\Models\Post;

interface PostRepositoryContract
{
    /**
     * @param array $data
     * @return array
     */
    public function createPost($data);

    /**
     * @param $id
     * @param $data
     * @param bool $allowCreateNew
     * @param bool $justUpdateSomeFields
     * @return array
     */
    public function updatePost($id, $data, $allowCreateNew = false, $justUpdateSomeFields = true);

    /**
     * @param PostModelContract $model
     * @param array $categories
     */
    public function syncCategories($model, $categories = null);

    /**
     * @param Post $post
     * @return array
     */
    public function getRelatedCategoryIds(PostModelContract $post);

    /**
     * @param Post $model
     * @param array $tags
     */
    public function syncTags($model, $tags = null);

    /**
     * @param Post $post
     * @return array
     */
    public function getRelatedTagIds(PostModelContract $post);
}
