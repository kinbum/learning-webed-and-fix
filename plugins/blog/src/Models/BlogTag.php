<?php namespace App\Plugins\Blog\Models;

use App\Plugins\Blog\Models\Contracts\BlogTagModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class BlogTag extends BaseModel implements BlogTagModelContract
{
    protected $table = 'blog_tags';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_tags', 'tag_id', 'post_id');
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
}
