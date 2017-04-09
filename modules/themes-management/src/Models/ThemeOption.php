<?php namespace App\Module\ThemesManagement\Models;

use App\Module\ThemesManagement\Models\Contracts\ThemeOptionModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class ThemeOption extends BaseModel implements ThemeOptionModelContract
{
    protected $table = 'theme_options';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = false;

    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
