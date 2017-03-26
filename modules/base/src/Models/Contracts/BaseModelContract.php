<?php namespace App\Module\Base\Models\Contracts;

interface BaseModelContract
{
    
    /*Get primary key*/
    public function getPrimaryKey ();

    /* Get the table associated with the model. -Lấy bảng kết hợp với mô hình.*/
    public function getTable ();
}
