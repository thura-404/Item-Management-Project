<?php

namespace App\DBTransactions\Categories;

use App\Classes\DBTransaction;
use App\Models\Category;

/**
 * handle delete category
 * @author Thura Win
 * @create 23/06/2023
 */
class DeleteCategory extends DBTransaction
{
    private $id;

    /**
     * assign id
     * @author Thura Win
     * @create 23/06/2023
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * delete category
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function process()
    {
        $deleteCategory = Category::find($this->id);
        $deleteCategory->delete();
        if (!$deleteCategory) {

            return ['status' => false, 'error' => 'Fails to delete Category'];
        }

        return ['status' => true, 'error' => ''];
    }
}
