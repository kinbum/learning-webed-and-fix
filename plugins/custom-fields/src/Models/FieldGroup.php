<?php namespace App\Plugins\CustomFields\Models;

use App\Plugins\CustomFields\Models\Contracts\FieldGroupModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class FieldGroup extends BaseModel implements FieldGroupModelContract
{
    protected $table = 'field_groups';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = true;

    public function fieldItems()
    {
        return $this->hasMany(FieldItem::class, 'field_group_id');
    }
}
