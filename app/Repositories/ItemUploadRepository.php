<?php

namespace App\Repositories;

use App\Models\ItemsUpload;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\ItemUploadInterface;


/**
 * Manage Database for Items.
 * @author Thura Win
 * @create 22/06/2023
 */
class ItemUploadRepository implements ItemUploadInterface
{
    /**
     * Get all ItemsUpload from DB.
     * @author Thura Win
     * @create 30/06/2023
     * @param  --
     * @return array
     */
    public function getAllItemsUpload()
    {
        return ItemsUpload::join('items', 'items.item_id', '=', 'items_uploads.item_id')
                    ->select('items.*', 'items_uploads.*')
                    ->get();
    }

    /**
     * Get ItemUpload from specific ID from DB.
     * @author Thura Win
     * @create 30/06/2023
     * @param  int $id
     * @return array
     */
    public function getItemUploadById($id)
    {
        return ItemsUpload::find($id)->toArray();
    }


    /**
     * Search ItemUpload from specific Item ID from DB.
     * @author Thura Win
     * @create 30/06/2023
     * @return array
     */
    public function checkItemUploadExist($itemId)
    {
        $searchItemsUpload = ItemsUpload::where('item_id', $itemId)->get();     
        return $searchItemsUpload;
    }
}
