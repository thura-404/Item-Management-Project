<?php

namespace App\DBTransactions\Items;

use App\Classes\DBTransaction;
use App\Models\Item;

class UpdateItem extends DBTransaction
{
    private $request, $id;

    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function process()
    {
        $updateItem = Item::find($this->id);
        $updateItem->item_id = $this->request['txtItemID'];
        $updateItem->item_code = $this->request['txtCode'];
        $updateItem->item_name = $this->request['txtName'];
        $updateItem->category_id = $this->request['cbocategories'];
        $updateItem->safety_stock = $this->request['txtStock'];
        $updateItem->received_date = $this->request['txtDate'];
        $updateItem->description = $this->request['txtDescription'];
        $updateItem->update();
        if (!$updateItem) {
           
            return ['status' => false, 'error' => 'Fails to Update Class'];
        }

        return ['status' => true, 'error' => ''];
    }
}
