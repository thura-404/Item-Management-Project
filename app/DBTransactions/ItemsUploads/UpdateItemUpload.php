<?php

namespace App\DBTransactions\ItemsUploads;

use App\Classes\DBTransaction;
use App\Models\ItemsUpload;
use Illuminate\Support\Facades\Log;

class UpdateItemUpload extends DBTransaction
{
    private $request, $id, $itemPrimaryId;

    public function __construct($request, $id, $itemPrimaryId)
    {
        $this->request = $request;
        $this->id = $id;
        $this->itemPrimaryId = $itemPrimaryId;
    }

    public function process()
    {
        $file = $this->request->filImage;

        $fileExtension = $file->extension();

        $fileName = $file->getClientOriginalName();

        $itemID = $this->request->txtItemID;
        $uniqueFileName = $itemID . $fileName;

        //public folder
        $fileSave = $file->move('upload_file', $uniqueFileName)->getPathname(); //save file        

        $fileSize = filesize($fileSave);

        $updateItemUpload = ItemsUpload::where('item_id', $this->id)->first();
        $updateItemUpload->file_path = $fileSave;
        $updateItemUpload->file_type = $fileExtension;
        $updateItemUpload->file_size = $fileSize;
        $updateItemUpload->item_id = $this->itemPrimaryId;
        $updateItemUpload->update();
        if (!$updateItemUpload) {

            return ['status' => false, 'error' => 'Fails to Upload Image'];
        }

        return ['status' => true, 'error' => ''];
    }
}
