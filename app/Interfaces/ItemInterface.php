<?php

namespace App\Interfaces;


interface ItemInterface
{
    public function getAllItems();

    public function getItemById($id);

    public function getMaxItemId();

    public function searchItems($data);

    public function downloadItems();
}
