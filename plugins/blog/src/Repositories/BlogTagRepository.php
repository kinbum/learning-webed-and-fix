<?php namespace App\Plugins\Blog\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;

use App\Plugins\Blog\Repositories\Contracts\BlogTagRepositoryContract;

class BlogTagRepository extends EloquentBaseRepository implements BlogTagRepositoryContract
{
    protected $rules = [
        'title' => 'string|max:255|required',
        'slug' => 'string|max:255|alpha_dash|unique:categories',
        'description' => 'string|max:1000|nullable',
        'status' => 'string|required|in:activated,disabled',
        'order' => 'integer|min:0',
        'created_by' => 'integer|min:0|required',
        'updated_by' => 'integer|min:0|required',
    ];

    protected $editableFields = [
        'title',
        'slug',
        'description',
        'status',
        'order',
        'created_by',
        'updated_by',
    ];

    /**
     * @param $data
     * @return array
     */
    public function createTag(array $data)
    {
        return $this->editWithValidate(0, $data, true);
    }

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function updateTag($id, array $data)
    {
        $this->unsetEditableFields('created_by');
        return $this->editWithValidate($id, $data, false, true);
    }
}
