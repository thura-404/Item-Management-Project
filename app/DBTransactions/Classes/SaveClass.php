<?php

namespace App\DBTransactions\Classes;

use App\Classes;
use App\Classes\DBTransaction;

class SaveClass extends DBTransaction
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function process()
    {
        $newClass = new Classes();
        $newClass->school_id = $this->request['school_id'];
        $newClass->class_name = $this->request['class_name'];
        $newClass->save();
        if (!$newClass) {
           
            return ['status' => false, 'error' => 'Fails to save Class'];
        }

        return ['status' => true, 'error' => ''];
    }
}
