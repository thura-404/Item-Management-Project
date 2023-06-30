<?php

namespace App\Interfaces;


interface ItemUploadInterface
{
    public function getAllItemsUpload();

    public function getItemUploadById($id);

    public function CheckItemUploadExist($itemId);
}
