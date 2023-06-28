<?php

namespace App\DBTransactions\Classes;

use App\Models\Classes;
use App\Classes\DBTransaction;

class DeleteClass extends DBTransaction
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {
        $updateClass = Classes::find($this->id);
        $updateClass->delete();
        if (!$updateClass) {
           
            return ['status' => false, 'error' => 'Fails to delete Class'];
        }

        return ['status' => true, 'error' => ''];
    }
}
