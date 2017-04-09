<?php namespace App\Plugins\Blog\Models;

use App\Plugins\Blog\Models\Contracts\CategoryModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class Category extends BaseModel implements CategoryModelContract
{
    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title', 'slug', 'status', 'parent_id', 'page_template',
        'description', 'content', 'thumbnail', 'keywords', 'order',
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public $timestamps = true;

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts() {
        return $this->belongsToMany(Post::class, 'posts_categories', 'category_id', 'post_id');
    }

    public function author() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
