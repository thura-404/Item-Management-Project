<?php

namespace App\DBTransactions\Items;

use App\Classes\DBTransaction;
use App\Models\Item;

class InactiveItem extends DBTransaction
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {
        $inactiveItem = Item::find($this->id);
        $inactiveItem->deleted_at = date('Y-m-d H:i:s');
        $inactiveItem->update();
        if (!$inactiveItem) {
           
            return ['status' => false, 'error' => 'Fails to save Class'];
        }

        return ['status' => true, 'error' => ''];
    }
}
