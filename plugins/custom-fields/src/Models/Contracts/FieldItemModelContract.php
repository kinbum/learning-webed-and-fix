<?php namespace App\Plugins\CustomFields\Models\Contracts;

interface FieldItemModelContract
{
    /**
     * @return mixed
     */
    public function fieldGroup();

    /**
     * @return mixed
     */
    public function parent();

    /**
     * @return mixed
     */
    public function child();
}
