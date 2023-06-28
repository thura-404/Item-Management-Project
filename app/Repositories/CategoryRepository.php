<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\CategoryInterface;


/**
 * Manage Database for Category.
 * @author Thura Win
 * @create 22/06/2023
 */
class CategoryRepository implements CategoryInterface
{
    /**
     * Get all categories from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  --
     * @return array
     */
    public function getAllCategories()
    {
        return Category::all();
    }

    /**
     * Get category from specific ID from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  int $id
     * @return array
     */
    public function getCategoryById($id)
    {
        return Category::find($id)->toArray();
    }

    /**
     * Get category from specific ID from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  int $id
     * @return array
     */
    public function getCategoryByName($name)
    {
        return Category::where('name', $name)->select('id')->first();
    }

    /**
     * Get category from specific ID from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  int $id
     * @return array
     */
    public function getUsedCategories()
    {
        $usedCategories = DB::table('categories')
            ->Join('items', 'categories.id', '=', 'items.category_id')
            ->select('categories.*')
            ->distinct()
            ->get()
            ->toArray();

        return $usedCategories;
    }

    /**
     * Get category from specific ID from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  int $id
     * @return array
     */
    public function getUnusedCategories()
    {
        $UnusedCategories = DB::table('categories')
            ->leftJoin('items', 'categories.id', '=', 'items.category_id')
            ->whereNull('items.category_id')
            ->select('categories.*')
            ->get();

        return $UnusedCategories;
    }
}
