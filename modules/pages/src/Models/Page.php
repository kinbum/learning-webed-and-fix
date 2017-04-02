<?php namespace App\Module\Pages\Models;

use App\Module\Pages\Models\Contracts\PageModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;
use App\Module\Users\Models\User;

class Page extends BaseModel implements PageModelContract
{
    protected $table = 'pages';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title', 'page_template', 'slug', 'description', 'content', 'thumbnail', 'keywords', 'status', 'order',
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function author () {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier () {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public $timestamps = false;
}
