<?php namespace App\Module\Pages\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Module\Pages\Repositories\Contracts\PageRepositoryContract;

class PageRepository extends EloquentBaseRepository implements PageRepositoryContract
{
    protected $rules = [
        'page_template' => 'string|max:255|nullable',
        'title' => 'string|max:255|required',
        'slug' => 'string|max:255|alpha_dash',
        'description' => 'string|max:1000|nullable',
        'content' => 'string|nullable',
        'thumbnail' => 'string|max:255|nullable',
        'keywords' => 'string|max:255|nullable',
        'created_by' => 'integer|min:0|required',
        'updated_by' => 'integer|min:0|required',
        'status' => 'string|required|in:activated,disabled',
        'order' => 'integer|min:0',
    ];

    protected $editableFields = [
        'page_template',
        'title',
        'slug',
        'description',
        'content',
        'thumbnail',
        'keywords',
        'created_by',
        'updated_by',
        'status',
        'order',
    ];

    /**
     * @param array $data
     * @return array
     */
    public function createPage(array $data)
    {
        return $this->editWithValidate(0, $data, true);
    }

    /**
     * @param int|null|BaseModelContract $id
     * @param array $data
     * @return array
     */
    public function updatePage($id, array $data)
    {
        return $this->editWithValidate($id, $data, false, true);
    }

    /**
     * @param int|BaseModelContract|array $id
     * @return array
     */
    public function deletePage($id)
    {
        $id = (array)$id;

        return $this->delete($id);
    }
}
