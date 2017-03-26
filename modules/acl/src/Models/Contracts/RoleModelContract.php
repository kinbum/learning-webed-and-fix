<?php namespace App\Module\Acl\Models\Contracts;

interface RoleModelContract
{
    public function permissions();
    public function users();
}
