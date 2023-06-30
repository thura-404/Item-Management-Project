<?php

namespace App\DBTransactions\Items;

use App\Classes\DBTransaction;
use App\Models\Item;

class ActiveItem extends DBTransaction
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {
        $inactiveItem = Item::find($this->id);
        $inactiveItem->deleted_at = null;
        $inactiveItem->update();
        if (!$inactiveItem) {
           
            return ['status' => false, 'error' => 'Fails to active'];
        }

        return ['status' => true, 'error' => ''];
    }
}
