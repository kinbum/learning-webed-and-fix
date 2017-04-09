<?php namespace App\Plugins\Blog\Repositories\Contracts;


interface BlogTagRepositoryContract
{
    public function createTag(array $data);
    public function updateTag($id, array $data);
}
