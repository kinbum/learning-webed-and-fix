<?php namespace App\Module\Menu\Models;

use App\Module\Base\Models\EloquentBase as BaseModel;
use App\Module\Menu\Models\Contracts\MenuModelContract;

class Menu extends BaseModel implements MenuModelContract
{
    protected $table = 'menus';

    protected $primaryKey = 'id';

    protected $fillable = [];
}
