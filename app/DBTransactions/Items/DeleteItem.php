<?php

namespace App\DBTransactions\Items;

use App\Classes\DBTransaction;
use App\Models\Item;

class DeleteItem extends DBTransaction
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {
        $deleteItem = Item::find($this->id);
        $deleteItem->delete();
        if (!$deleteItem) {
           
            return ['status' => false, 'error' => $deleteItem];
        }

        return ['status' => true, 'error' => ''];
    }
}
