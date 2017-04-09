<?php namespace App\Plugins\Blog\Models;

use App\Plugins\Blog\Models\Contracts\PostModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;
use Carbon\Carbon;

use App\Module\Users\Models\User;

class Post extends BaseModel implements PostModelContract
{
    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = true;


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'posts_categories', 'post_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'posts_tags', 'post_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedAtAttribute($value) {
    	return Carbon::parse($value)->format('d-m-Y');
    }
}
