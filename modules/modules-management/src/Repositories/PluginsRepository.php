<?php namespace App\Module\ModulesManagement\Repositories;

use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Module\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;

class PluginsRepository extends EloquentBaseRepository implements PluginsRepositoryContract
{
    protected $rules = [
        'alias' => 'string|max:255|alpha_dash',
        'installed_version' => 'string|max:255',
    ];

    protected $editableFields = [
        'alias',
        'installed_version',
        'enabled',
        'installed',
    ];

    /**
     * @param $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->model->where('alias', '=', $alias)->first();
    }
}
