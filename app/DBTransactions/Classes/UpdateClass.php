<?php

namespace App\DBTransactions\Classes;

use App\Classes;
use App\Classes\DBTransaction;

class UpdateClass extends DBTransaction
{
    private $request, $id;

    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function process()
    {
        $updateClass = Classes::find($this->id);
        $updateClass->school_id = $this->request['school_id'];
        $updateClass->class_name = $this->request['class_name'];
        $updateClass->update();
        if (!$updateClass) {
           
            return ['status' => false, 'error' => 'Fails to save Class'];
        }

        return ['status' => true, 'error' => ''];
    }
}
