<?php namespace App\Plugins\Blog\Models\Contracts;

interface PostModelContract
{
    public function categories();
    
    public function tags();
}
