<?php namespace App\Module\ModulesManagement\Models;

use App\Module\ModulesManagement\Models\Contracts\PluginsModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class Plugins extends BaseModel implements PluginsModelContract
{
    protected $table = 'plugins';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = false;

    public function setEnabledAttribute($value)
    {
        $this->attributes['enabled'] = (int)!!$value;
    }

    public function setInstalledAttribute($value)
    {
        $this->attributes['installed'] = (int)!!$value;
    }
}
