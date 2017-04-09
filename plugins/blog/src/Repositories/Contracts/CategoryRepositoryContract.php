<?php namespace App\Plugins\Blog\Repositories\Contracts;

use App\Module\Base\Models\Contracts\BaseModelContract;

interface CategoryRepositoryContract
{
    /**
     * @param array $data
     * @return array
     */
    public function createCategory(array $data);

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return array
     */
    public function updateCategory($id, array $data);

    /**
     * @param int|BaseModelContract|array $id
     * @return array
     */
    // public function deleteCategory($id);

    /**
     * @param $id
     * @param bool $justId
     * @return array
     */
    public function getChildren($id, $justId = true);

    /**
     * @param $id
     * @return Category
     */
    public function getParent($id);

    /**
     * @param $id
     * @return array|null
     */
    public function getAllRelatedChildrenIds($id);
}
