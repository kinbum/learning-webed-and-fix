<?php namespace App\Plugins\CustomFields\Models;

use App\Plugins\CustomFields\Models\Contracts\FieldItemModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class FieldItem extends BaseModel implements FieldItemModelContract
{
    protected $table = 'field_items';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = false;
    
    public function fieldGroup()
    {
        return $this->belongsTo(FieldGroup::class, 'field_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(FieldItem::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(FieldItem::class, 'parent_id');
    }
}
