<?php namespace App\Module\ThemesManagement\Repositories;

use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;
use App\Module\ThemesManagement\Repositories\Contracts\ThemeRepositoryContract;

class ThemeRepository extends EloquentBaseRepository implements ThemeRepositoryContract
{
    protected $rules = [

    ];

    protected $editableFields = [
        '*',
    ];

    /**
     * @param $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->where('alias', '=', $alias)->first();
    }
}
