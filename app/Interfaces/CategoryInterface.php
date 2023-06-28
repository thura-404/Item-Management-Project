<?php

namespace App\Interfaces;

interface CategoryInterface
{
    public function getAllCategories();

    public function getCategoryById($id);

    public function getCategoryByName($name);

    public function getUsedCategories();

    public function getUnusedCategories();
}