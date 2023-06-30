<?php

namespace App\DBTransactions\ItemsUploads;

use App\Models\ItemsUpload;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\Log;

class DeleteItemUpload extends DBTransaction
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {
        $deleteItemUpload = ItemsUpload::where('item_id', $this->id);
        $deleteItemUpload->delete();
        if (!$deleteItemUpload) {
            return ['status' => false, 'error' => $deleteItemUpload];
        }

        return ['status' => true, 'error' => ''];
    }
}
