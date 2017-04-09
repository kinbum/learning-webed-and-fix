<?php namespace App\Plugins\CustomFields\Models;

use App\Plugins\CustomFields\Models\Contracts\CustomFieldModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class CustomField extends BaseModel implements CustomFieldModelContract
{
    protected $table = 'custom_fields';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = false;

    public function useCustomFields()
    {
        // return $this->morphTo();
    }
    public function getResolvedValueAttribute()
    {
        switch ($this->type) {
            case 'repeater':
                try {
                    return json_decode($this->value, true);
                } catch (\Exception $exception) {
                    return [];
                }
                break;
            default:
                return $this->value;
                break;
        }
    }
}
