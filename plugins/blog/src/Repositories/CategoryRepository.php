<?php namespace App\Plugins\Blog\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;

use App\Plugins\Blog\Models\Contracts\CategoryModelContract;
use App\Plugins\Blog\Repositories\Contracts\CategoryRepositoryContract;

class CategoryRepository extends EloquentBaseRepository implements CategoryRepositoryContract
{

    protected $rules = [
        'parent_id' => 'integer|min:0|nullable',
        'page_template' => 'string|max:255|nullable',
        'title' => 'string|max:255|required',
        'slug' => 'string|max:255|alpha_dash|unique:categories',
        'description' => 'string|max:1000|nullable',
        'content' => 'string|nullable',
        'thumbnail' => 'string|max:255|nullable',
        'keywords' => 'string|max:255|nullable',
        'status' => 'string|required|in:activated,disabled',
        'order' => 'integer|min:0',
        'created_by' => 'integer|min:0|required',
        'updated_by' => 'integer|min:0|required',
    ];

    protected $editableFields = [
        'parent_id',
        'title',
        'page_template',
        'slug',
        'description',
        'content',
        'thumbnail',
        'keywords',
        'status',
        'order',
        'created_by',
        'updated_by',
    ];

    /**
     * @param array $data
     * @return array
     */
    public function createCategory(array $data)
    {
        return $this->editWithValidate(0, $data, true);
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return array
     */
    public function updateCategory($id, array $data)
    {
        $this->unsetEditableFields('created_by');
        return $this->editWithValidate($id, $data, false, true);
    }

    /**
     * @param int|BaseModelContract|array $id
     * @return array
     */
    public function deleteModel($id)
    {
        if (!$id) {
            return response_with_messages('Model not exists', true, \Constants::ERROR_CODE);
        }
        $result = $this->delete($id);
        if ($result['error']) {
            return $result;
        }
        return response_with_messages('Deleted', false, \Constants::SUCCESS_NO_CONTENT_CODE);
    }

    public function getChildren($id, $justId = true) {
        if($id instanceof CategoryModelContract) {
            $model = $id;
        } else {
            $model = $this->find($id);
        }
        if(!$model) {
            return null;
        }
        $children = $model->children();
        if($justId === true) {
            $result = [];
            $children = $children->select('id')->get();
            foreach ($children as $child) {
                $result[] = $child->id;
            }
            return $result;
        }
        return $children->get();
    }

    /**
     * @param $id
     * @return Category
     */
    public function getParent($id)
    {
        if ($id instanceof CategoryModelContract) {
            $model = $id;
        } else {
            $model = $this->find($id);
        }
        if (!$model) {
            return null;
        }

        return $model->parent()->first();
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getAllRelatedChildrenIds($id)
    {
        if ($id instanceof CategoryModelContract) {
            $model = $id;
        } else {
            $model = $this->find($id);
        }
        if (!$model) {
            return null;
        }

        $result = [];

        $children = $model->children();
        $children = $children->select('id')->get();
        foreach ($children as $child) {
            $result[] = $child->id;
            $result = array_merge($this->getAllRelatedChildrenIds($child), $result);
        }
        return array_unique($result);
    }
}
