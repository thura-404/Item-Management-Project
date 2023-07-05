<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Employee;
use App\Interfaces\ItemInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


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
            ->leftJoin('items_uploads', 'items.id', '=', 'items_uploads.item_id')
            ->select('items.*', 'categories.name as name', 'items_uploads.file_path as image')
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
        return Item::join('categories', 'items.category_id', '=', 'categories.id')
        ->leftJoin('items_uploads', 'items.id', '=', 'items_uploads.item_id')
        ->select('items.*', 'categories.name as name', 'items_uploads.file_path as image')
            ->find($id);
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
     * @author Thura Win
     * @create 26/06/2023
     * @return array
     */
    public function searchItems($data)
    {
        $searchItems = Item::join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as name');

        if (!empty($data['txtItemId'])) {
            $searchItems->where('items.item_id', "LIKE", $data['txtItemId']);
        }

        if (!empty($data['txtCode'])) {
            $searchItems->where('items.item_code', "LIKE", $data['txtCode']);
        }

        if (!empty($data['txtItemName'])) {
            $searchItems->where('items.item_name', "LIKE", $data['txtItemName']);
        }

        if (!empty($data['cboCategories'])) {
            $searchItems->where('categories.id', "=", $data['cboCategories']);
        }

        return $searchItems;
    }

    /**
     * Download Items.
     * @author Thura Win
     * @create 30/06/2023
     * @return array
     */
    public function downloadItems()
    {
        $searchItems = Item::join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.item_id', 'items.item_code', 'items.item_name', 'categories.name', 'items.safety_stock', 'items.received_date', 'items.description')
            ->get();
        return $searchItems;
    }

    /**
     * suggest Items.
     * @author Thura Win
     * @create 03/07/2023
     * @return array
     */
    public function autoCompleteItems($data)
    {
        $suggestItems = Item::where('item_id', 'like', '%' . $data['term'] . '%')
            ->orWhere('item_code', 'like', '%' . $data['term'] . '%')
            ->orWhere('item_name', 'like', '%' . $data['term'] . '%')
            ->limit(10)
            ->get(['item_id', 'item_code', 'item_name']);
        return $suggestItems;
    }
}
