<?php

namespace App\DBTransactions\Categories;

use App\Classes\DBTransaction;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

/**
 * save category
 * @author Thura Win
 * @create 23/06/2023
 */
class SaveCategory extends DBTransaction
{
    private $request;

    /**
     * assign request
     * @author Thura Win
     * @create 23/06/2023
     */ 
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * save category
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function process()
    {
        try {
            $newCategory = new Category();
            $newCategory->name = $this->request['name'];
            $newCategory->save();

            if (!$newCategory) { 
                return ['status' => false, 'error' => 'Failed to save category.'];
            }
            return ['status' => true, 'categoryId' => $newCategory->id];
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
