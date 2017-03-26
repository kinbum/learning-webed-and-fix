<?php namespace App\Module\Base\Models;

use App\Module\Base\Models\Contracts\ViewTrackerModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class ViewTracker extends BaseModel implements ViewTrackerModelContract
{
    protected $table = 'view_trackers';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = false;

    public function getCountAttribute ( $value ) {
        return (int)$value;
    }
}
