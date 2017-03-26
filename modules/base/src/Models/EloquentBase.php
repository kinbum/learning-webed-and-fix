<?php namespace App\Module\Base\Models;

use Illuminate\Database\Eloquent\Model;
use App\Module\Base\Models\Contracts\BaseModelContract;

abstract class EloquentBase extends Model implements BaseModelContract
{
    protected $primaryKey = false;

    public function getPrimaryKey() {
        return $this->primaryKey;
    }
}
