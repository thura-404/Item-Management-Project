<?php

namespace App\DBTransactions\ItemsUploads;

use App\Models\ItemsUpload;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\Log;

/**
 * save item image to database
 * @author Thura Win
 * @create 23/06/2023
 */
class SaveItemUpload extends DBTransaction
{
    private $request, $itemPrimaryId;

    /**
     * assign request and item primary id
     * @author Thura Win
     * @create 23/06/2023
     */
    public function __construct($request, $itemPrimaryId)
    {
        $this->request = $request;
        $this->itemPrimaryId = $itemPrimaryId;
    }

    /**
     * save item image to database
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
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

        $newItemsUpload = new ItemsUpload();
        $newItemsUpload->file_path = $fileSave;
        $newItemsUpload->file_type = $fileExtension;
        $newItemsUpload->file_size = $fileSize;
        $newItemsUpload->item_id = $this->itemPrimaryId;
        $newItemsUpload->save();

        if (!$newItemsUpload) {

            return ['status' => false, 'error' => 'Fails to save Item Image'];
        }

        return ['status' => true, 'error' => ''];
    }
}
