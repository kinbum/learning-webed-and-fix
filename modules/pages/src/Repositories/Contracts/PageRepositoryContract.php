<?php namespace App\Module\Pages\Repositories\Contracts;

use App\Module\Base\Models\Contracts\BaseModelContract;

interface PageRepositoryContract
{
    /**
     * @param array $data
     * @return array
     */
    public function createPage(array $data);

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return array
     */
    public function updatePage($id, array $data);

    /**
     * @param int|BaseModelContract|array $id
     * @return array
     */
    public function deletePage($id);
}
