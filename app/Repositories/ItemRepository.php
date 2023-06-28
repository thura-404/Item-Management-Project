<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\ItemInterface;
use App\Models\Item;


/**
 * Manage Database for Items.
 * @author Thura Win
 * @create 22/06/2023
 */
class ItemRepository implements ItemInterface
{
    /**
     * Get all Items from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  --
     * @return array
     */
    public function getAllItems()
    {
        return Item::join('categories', 'items.category_id', '=', 'categories.id')
                    ->select('items.*', 'categories.name as name')
                    ->orderBy('item_id', 'desc')
                    ->paginate(20);
    }

    /**
     * Get Item from specific ID from DB.
     * @author Thura Win
     * @create 22/06/2023
     * @param  int $id
     * @return array
     */
    public function getItemById($id)
    {
        return Item::find($id)->toArray();
    }


    /**
     * Get Max Item ID from DB.
     *
     * If no records exist, return 10001.
     * If records exist, return the maximum Item ID + 1.
     * @author Thura Win
     * @create 22/06/2023
     * @return int
     */
    public function getMaxItemId()
    {
        $maxId = Item::max('item_id');

        if ($maxId === null) {
            return 10001;
        }

        return $maxId + 1;
    }

    /**
     * Search Items.
     *
     * If no records exist, return flase.
     * If records exist, return the results.
     * @author Thura Win
     * @create 26/06/2023
     * @return array
     */
    public function searchItems($data)
    {
        $searchItems = Item::join('categories', 'items.category_id', '=', 'categories.id')
                        ->where([['items.item_id', 'like', '%' . $data->txtItemID . '%'],
                                ['items.item_code', 'like', '%' . $data->txtCode . '%'],
                                ['items.item_name', 'like', '%' . $data->txtItemName . '%'],
                                ['categories.name', 'like', '%' . $data->cboCategories . '%']])
                        ->first()
                        ->toArray();
        
        return $searchItems;
    }
}
