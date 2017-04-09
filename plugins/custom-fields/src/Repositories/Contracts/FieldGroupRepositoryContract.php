<?php namespace App\Plugins\CustomFields\Repositories\Contracts;

use App\Module\Base\Models\Contracts\BaseModelContract;

interface FieldGroupRepositoryContract
{
    /**
     * @param array $data
     * @return array
     */
    public function createFieldGroup(array $data);

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return array
     */
    public function updateFieldGroup($id, array $data);

    /**
     * @param int|BaseModelContract|array $id
     * @return array
     */
    public function deleteFieldGroup($id);
}
