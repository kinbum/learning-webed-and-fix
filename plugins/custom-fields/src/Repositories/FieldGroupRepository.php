<?php namespace App\Plugins\CustomFields\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;

use App\Plugins\CustomFields\Repositories\Contracts\FieldGroupRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\FieldItemRepositoryContract;

class FieldGroupRepository extends EloquentBaseRepository implements FieldGroupRepositoryContract
{
    protected $rules = [
        'order' => 'integer|min:0',
        'rules' => 'json|required',
        'title' => 'string|required|max:255',
        'status' => 'required|in:activated,disabled',
        'created_by' => 'integer|min:0|required',
        'updated_by' => 'integer|min:0|required',
    ];

    protected $editableFields = [
        'order',
        'rules',
        'title',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $fieldItemRepository;
    protected $customFieldRepository;

    public function __construct(BaseModelContract $model) {
        parent::__construct($model);

        $this->fieldItemRepository = app()->make(FieldItemRepositoryContract::class);
        $this->customFieldRepository = app()->make(CustomFieldRepositoryContract::Class);

    }

    public function getGroupItems($groupId, $parentId = null) {
        return $this->fieldItemRepository
                    ->where([
                        'field_group_id' => $groupId,
                        'parent_id' => $parentId
                    ])
                    ->orderBy('order', 'ASC')
                    ->get();
    }

    public function getFieldGroupItems($groupId, $parentId = null, $withValue = false, $morphClass = null, $morphId = null)
    {
        $result = [];

        $fieldItems = $this->getGroupItems($groupId, $parentId);

        foreach ($fieldItems as $key => $row) {
            $item = [
                'id' => $row->id,
                'title' => $row->title,
                'slug' => $row->slug,
                'instructions' => $row->instructions,
                'type' => $row->type,
                'options' => json_decode($row->options),
                'items' => $this->getFieldGroupItems($groupId, $row->id, $withValue, $morphClass, $morphId),
            ];
            if ($withValue === true) {
                if ($row->type === 'repeater') {
                    $item['value'] = $this->getRepeaterValue($item['items'], $this->getFieldItemValue($row, $morphClass, $morphId));
                } else {
                    $item['value'] = $this->getFieldItemValue($row, $morphClass, $morphId);
                }
            }

            $result[] = $item;
        }

        return $result;
    }
    protected function getFieldItemValue($fieldItem, $morphClass, $morphId)
    {
        if (is_object($morphClass)) {
            $morphClass = get_class($morphClass);
        }

        $field = $this->customFieldRepository
            ->where([
                'use_for' => $morphClass,
                'use_for_id' => $morphId,
                'field_item_id' => $fieldItem->id,
                //'slug' => $fieldItem->slug,
            ])->first();

        return ($field) ? $field->value : null;
    }

    protected function getRepeaterValue($items, $data)
    {
        if (!$items) {
            return null;
        }
        $data = ($data) ?: [];
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        $result = [];
        foreach ($data as $key => $row) {
            $cloned = $items;
            foreach ($cloned as $keyItem => $item) {
                foreach ($row as $currentData) {
                    if ((int)$item['id'] === (int)$currentData['field_item_id']) {
                        if ($item['type'] === 'repeater') {
                            $item['value'] = $this->getRepeaterValue($item['items'], $currentData['value']);
                        } else {
                            $item['value'] = $currentData['value'];
                        }
                        $cloned[$keyItem] = $item;
                    }
                }
            }
            $result[$key] = $cloned;
        }
        return $result;
    }
    
    /**
     * @param array $data
     * @param bool $withEvent
     * @return array
     */
    public function createFieldGroup(array $data)
    {
        $result = $this->editWithValidate(0, [
            'order' => $data['order'],
            'rules' => $data['rules'],
            'title' => $data['title'],
            'status' => $data['status'],
            'created_by' => $data['updated_by'],
            'updated_by' => $data['updated_by'],
        ], true, false);

        if ($result['error']) {
            return response_with_messages($result['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $result['data'];

        if (isset($data['group_items'])) {
            $this->editGroupItems(json_decode($data['group_items'], true), $object->id);
        }

        return response_with_messages('Field group updated successfully', false, \Constants::SUCCESS_CODE, $object);
    }

    /**
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateFieldGroup($id, array $data)
    {
        $result = $this->editWithValidate($id, [
            'order' => $data['order'],
            'rules' => $data['rules'],
            'title' => $data['title'],
            'status' => $data['status'],
            'updated_by' => $data['updated_by'],
        ], false, true);

        if ($result['error']) {
            return response_with_messages($result['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $result['data'];

        if (array_get($data, 'deleted_items')) {
            $this->deleteGroupItems(json_decode($data['deleted_items'], true));
        }

        if (array_get($data, 'group_items')) {
            $this->editGroupItems(json_decode($data['group_items'], true), $id);
        }

        return response_with_messages('Field group updated successfully', false, \Constants::SUCCESS_CODE, $object);
    }

    /**
     * @param int|array $id
     * @return mixed
     */
    public function deleteFieldGroup($id)
    {
        $result = $this->delete($id);

        return $result;
    }

    /**
     * @param array $items
     * @param int $groupId
     * @param int|null $parentId
     */
    protected function editGroupItems($items, $groupId, $parentId = null)
    {
        $position = 0;
        $items = (array)$items;
        foreach ($items as $key => $row) {
            $position++;

            $id = (int)$row['id'];

            $data = [
                'field_group_id' => $groupId,
                'parent_id' => $parentId,
                'title' => $row['title'],
                'order' => $position,
                'type' => $row['type'],
                'options' => json_encode($row['options']),
                'instructions' => $row['instructions'],
                'slug' => (str_slug($row['slug'], '_')) ?: str_slug($row['title'], '_'),
            ];

            $result = $this->fieldItemRepository->updateFieldItem($id, $data);

            if (!$result['error']) {
                $this->editGroupItems($row['items'], $groupId, (int)$result['data']->id);
            }
        }
    }

    /**
     * @param array|int $items
     */
    protected function deleteGroupItems($items)
    {
        return $this->fieldItemRepository->delete((array)$items);
    }
}
