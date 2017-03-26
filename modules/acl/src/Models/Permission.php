<?php namespace App\Module\Acl\Models;

use App\Module\Acl\Models\Contracts\PermissionModelContract;
use App\Module\Base\Models\EloquentBase as BaseModel;

class Permission extends BaseModel implements PermissionModelContract
{
    protected $table = 'permissions';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'slug', 'module'];

    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions', 'permission_id', 'role_id');
    }
}
