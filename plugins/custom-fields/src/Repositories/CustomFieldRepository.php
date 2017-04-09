<?php namespace App\Plugins\CustomFields\Repositories;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;

use App\Plugins\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;

class CustomFieldRepository extends EloquentBaseRepository implements CustomFieldRepositoryContract
{
    protected $rules = [
        'use_for' => 'required',
        'use_for_id' => 'required|integer',
        'parent_id' => 'integer',
        'type' => 'required|string|max:255',
        'slug' => 'required|between:3,255|alpha_dash',
        'value' => 'nullable|string',
    ];

    protected $editableFields = [
        'use_for',
        'use_for_id',
        'parent_id',
        'type',
        'slug',
        'value',
    ];

}
