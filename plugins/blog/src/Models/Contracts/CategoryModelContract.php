<?php namespace App\Plugins\Blog\Models\Contracts;

interface CategoryModelContract
{
    public function posts();

    public function parent();

    public function children();
}
