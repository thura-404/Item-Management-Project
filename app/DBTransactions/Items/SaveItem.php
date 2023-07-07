<?php

namespace App\DBTransactions\Items;

use App\Models\Item;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\Log;

/**
 * save item to database
 * @author Thura Win
 * @create 23/06/2023
 */
class SaveItem extends DBTransaction
{
    private $request, $item_id;

    /**
     * assign request
     * @author Thura Win
     * @create 23/06/2023
     */
    public function __construct($request, $item_id)
    {
        $this->request = $request;
        $this->item_id = $item_id;
    }

    /**
     * save item to database
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function process()
    {

        $newItem = new Item();
        $newItem->item_id = $this->item_id;
        $newItem->item_code = $this->request['txtCode'];
        $newItem->item_name = $this->request['txtName'];
        $newItem->category_id = $this->request['cbocategories'];
        $newItem->safety_stock = $this->request['txtStock'];
        $newItem->received_date = $this->request['txtDate'];
        $newItem->description = $this->request['txtDescription'];
        $newItem->save();

        if ($newItem) {
            $primaryKey = $newItem->id;
            return ['status' => true, 'primary_key' => $primaryKey];
        } else {
            return ['status' => false, 'error' => 'Fails to save Item'];
        }
    }
}
