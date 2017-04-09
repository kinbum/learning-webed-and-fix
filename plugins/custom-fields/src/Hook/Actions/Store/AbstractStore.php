<?php namespace App\Plugins\CustomFields\Hook\Actions\Store;

use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Plugins\CustomFields\Models\CustomField;
use App\Plugins\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\FieldGroupRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\FieldItemRepositoryContract;
use App\Plugins\CustomFields\Repositories\CustomFieldRepository;
use App\Plugins\CustomFields\Repositories\FieldGroupRepository;
use App\Plugins\CustomFields\Repositories\FieldItemRepository;

abstract class AbstractStore
{
    /**
     * @var EloquentBaseRepository
     */
    protected $repository;

    /**
     * @var CustomFieldRepository
     */
    protected $customFieldRepository;

    /**
     * @var FieldGroupRepository
     */
    protected $fieldGroupRepository;

    /**
     * @var FieldItemRepository
     */
    protected $fieldItemRepository;

    /**
     * Determine if the related modules enabled
     * @var bool
     */
    protected $moduleEnabled = false;

    /**
     * @var array|\Illuminate\Http\Request|string
     */
    protected $request;

    /**
     * @var string
     */
    protected $repositoryInterface = '';

    public function __construct()
    {
        if (interface_exists($this->repositoryInterface)) {
            $this->moduleEnabled = true;

            $this->repository = app($this->repositoryInterface);

            $this->customFieldRepository = app(CustomFieldRepositoryContract::class);
            $this->fieldGroupRepository = app(FieldGroupRepositoryContract::class);
            $this->fieldItemRepository = app(FieldItemRepositoryContract::class);

            $this->request = request();
        }
    }

    /**
     * @param \App\Module\Base\Models\EloquentBase $model
     * @return $this
     */
    public function setCustomFieldRelationship($model)
    {
        return $model->morphMany(CustomField::class, 'useCustomFields', 'use_for', 'use_for_id');
    }

    /**
     * @param BaseModelContract|int $owner
     * @param array $data
     */
    public function saveCustomFields($owner, array $data)
    {
        if (!$owner instanceof BaseModelContract) {
            $owner = $this->repository->find($owner);
        }

        if (!$owner) {
            return;
        }

        foreach ($data as $key => $row) {
            $this->saveCustomField($owner, $row, false);
        }

        $this->flushCache();
    }

    /**
     * Save custom field
     * @param int|BaseModelContract $owner
     * @param $data
     * @param bool $flushCache
     */
    public function saveCustomField($owner, $data, $flushCache = true)
    {
        if (!$owner instanceof BaseModelContract) {
            $owner = $this->find($owner);
        }

        if (!$owner) {
            return;
        }

        $class = $this->customFieldRepository->getModel()->getMorphClass();

        $this->setCustomFieldRelationship($owner);

        $currentMeta = $this->setCustomFieldRelationship($owner)
            ->where([
                'field_item_id' => $data->id,
                'slug' => $data->slug,
            ])
            ->first();

        $value = $this->parseFieldValue($data);

        if (!is_string($value)) {
            $value = json_encode($value);
        }

        $isOK = false;

        if ($currentMeta) {
            $result = $this->customFieldRepository->editWithValidate($currentMeta, [
                'type' => $data->type,
                'value' => $value,
            ], false, true);
            if ($result['error']) {
                return;
            }
            $isOK = true;
        } else {
            $meta = new $class;
            $meta->field_item_id = $data->id;
            $meta->slug = $data->slug;
            $meta->type = $data->type;
            $meta->value = $value;

            try {
                $this->setCustomFieldRelationship($owner)
                    ->save($meta);
                $isOK = true;
            } catch (\Exception $exception) {

            }
        }

        if ($flushCache && $isOK === true) {
            $this->flushCache();
        }
    }

    /**
     * Get field value
     * @param $field
     * @return array|string
     */
    private function parseFieldValue($field)
    {
        switch ($field->type) {
            case 'repeater':
                if (!isset($field->value)) {
                    return [];
                }

                $value = [];
                foreach ($field->value as $row) {
                    $groups = [];
                    foreach ($row as $item) {
                        $groups[] = [
                            'field_item_id' => $item->id,
                            'type' => $item->type,
                            'slug' => $item->slug,
                            'value' => $this->parseFieldValue($item),
                        ];
                    }
                    $value[] = $groups;
                }
                return $value;
                break;
            case 'checkbox':
                $value = isset($field->value) ? (array)$field->value : [];
                break;
            default:
                $value = isset($field->value) ? $field->value : '';
                break;
        }
        return $value;
    }

    /**
     * Flush repository cache
     */
    protected function flushCache()
    {
    }

    /**
     * @param $id
     * @param array $result
     * @param BaseAdminController $controller
     */
    public function afterSaveContent($id, array $result, BaseAdminController $controller)
    {
        if ($this->moduleEnabled !== true) {
            return;
        }

        /**
         * Plugin Pages enabled
         */
        if (!array_get($result, 'error', false) && $this->request->has('custom_fields')) {
            $customFieldsData = parse_custom_fields_raw_data($this->request->get('custom_fields', []));

            if (!$customFieldsData) {
                return;
            }

            /**
             * Get object from result
             */
            $object = array_get($result, 'data', null);

            /**
             * Has custom fields
             */
            if ($customFieldsData && $object) {
                try {
                    /**
                     * Begin save custom fields
                     */
                    $this->saveCustomFields($object, $customFieldsData);
                    flash_messages()->addMessages('Related custom fields updated', 'success');
                } catch (\Exception $exception) {
                    flash_messages()->addMessages($exception->getMessage(), 'error');
                }
            }
        }
    }
}
