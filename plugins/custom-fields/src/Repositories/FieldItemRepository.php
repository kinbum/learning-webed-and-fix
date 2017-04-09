<?php namespace App\Plugins\CustomFields\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Plugins\CustomFields\Repositories\Contracts\FieldItemRepositoryContract;

class FieldItemRepository extends EloquentBaseRepository implements FieldItemRepositoryContract
{
    protected $rules = [
        'field_group_id' => 'required|integer|min:0',
        'parent_id' => '',
        'order' => 'integer|min:0',
        'title' => 'required|max:255',
        'slug' => 'alpha_dash|required|max:255',
        'type' => 'max:100|required',
        'instructions' => '',
        'options' => 'required|json',
    ];

    protected $editableFields = [
        '*',
    ];

    /**
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateFieldItem($id, array $data)
    {
        $data['slug'] = $this->makeUniqueSlug($id, $data['field_group_id'], $data['parent_id'], $data['slug']);
        return $this->editWithValidate($id, $data, true, true);
    }

    /**
     * @param int $id
     * @param int $fieldGroupId
     * @param int $parentId
     * @param string $slug
     * @return string
     */
    private function makeUniqueSlug($id, $fieldGroupId, $parentId, $slug)
    {
        $isExist = $this->where([
            'slug' => $slug,
            'field_group_id' => $fieldGroupId,
            'parent_id' => $parentId
        ])->first();
        if ($isExist && (int)$id != (int)$isExist->id) {
            return $slug . '_' . time();
        }
        return $slug;
    }
}
