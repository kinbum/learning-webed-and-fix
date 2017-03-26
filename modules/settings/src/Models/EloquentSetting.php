<?php namespace App\Module\Settings\Models;

use App\Module\Base\Models\EloquentBase as BaseModel;

class EloquentSetting extends BaseModel {
    protected $table = 'settings';
    protected $primaryKey = 'id';
}