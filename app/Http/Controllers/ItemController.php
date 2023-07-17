<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\Item;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use App\Interfaces\ItemInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\CategoryInterface;
use App\DBTransactions\Items\SaveItem;
use App\Interfaces\ItemUploadInterface;
use Illuminate\Support\Facades\Session;
use App\DBTransactions\Items\ActiveItem;
use App\DBTransactions\Items\DeleteItem;
use App\DBTransactions\Items\UpdateItem;
use App\Exports\ItemRegiserFormatExport;
use App\Http\Requests\ItemDeleteRequest;
use App\Http\Requests\ItemSearchRequest;
use App\Imports\ItemRegisterExcelImport;
use App\DBTransactions\Items\PDFDownload;
use App\Http\Requests\ExcelImportRequest;
use App\DBTransactions\Items\InactiveItem;
use App\Http\Requests\ItemRegisterRequest;
use App\DBTransactions\ItemsUploads\SaveItemUpload;
use App\DBTransactions\ItemsUploads\DeleteItemUpload;
use App\DBTransactions\ItemsUploads\UpdateItemUpload;

class ItemController extends Controller
{
    private $itemInterface, $categoryInterface, $itemsUploadInterface;

    public function __construct(Item $item, ItemInterface $itemInterface, CategoryInterface $categoryInterface, ItemUploadInterface $itemsUploadInterface)
    {
        $this->itemInterface = $itemInterface;
        $this->categoryInterface = $categoryInterface;
        $this->itemsUploadInterface = $itemsUploadInterface;
    }


    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = $this->itemInterface->getAllItems();
        $categories = $this->categoryInterface->getUsedCategories();
        $totalRecord = $items->count();
        return view('pages.index')->with('items', $items->paginate(20))->with('total', $totalRecord)->with('categories', $categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @return \Illuminate\Http\Response
     */
    public function getAllItems()
    {
        //
        $items = $this->itemInterface->getAllItems();
        return $items;
    }

    /**
     * Search a listing of the resource.
     *
     * 
     * @author Thura Win
     * @create 26/06/2023
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //
        try {
            $categories = $this->categoryInterface->getUsedCategories();
            $formData = [
                'txtItemId' => $request->input('txtItemId'),
                'txtCode' => $request->input('txtCode'),
                'txtItemName' => $request->input('txtItemName'),
                'cboCategories' => $request->input('cboCategories'),
            ];

            // Handle POST request
            // Perform form processing, database operations, etc.
            $searchItems = $this->itemInterface->searchItems($request); // search items
            $totalRecord = $searchItems->count();
            $searchResult = $searchItems->paginate(20);
            if (!$searchResult) {
                return view('pages.index')->with(['items' => $searchResult])->with('total', $totalRecord)->with('categories', $categories);
            }
            return view('pages.index')->with('items', $searchResult)->with('categories', $categories)->with('formData', $formData)->with('search', true)->with('total', $totalRecord);
        } catch (\Exception $e) {
            return view('pages.index')->withErrors(['message' => $e->getMessage()])->with('categories', $categories) - with('total', $totalRecord);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $itemId = $this->itemInterface->getMaxItemId();
        $categories = $this->categoryInterface->getAllCategories();

        return view('pages.register')->with([
            'itemId' => $itemId,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @return \Illuminate\Http\Response
     */
    public function createExcel()
    {
        return view('pages.excel-register');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ExcelFormatDownload()
    {
        try {
            return Excel::download(new ItemRegiserFormatExport, 'items_register_format.xlsx');
        } catch (\Exception $e) {
            return redirect()->route('items.excel-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Export a listing of the resource.
     *
     * @author Thura Win
     * @create 30/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return Excel File.
     */
    public function exportAllItems(Request $request, $type)
    {
        try {
            $itemsToDownload = $this->itemInterface->downloadItems();
            if ($type == 'excel') {

                return Excel::download(new ItemsExport($itemsToDownload), 'items.xlsx');
            } else if ($type == 'pdf') {
                $result = new PDFDownload($itemsToDownload);
                return $result->downloadItemsAsPDF();
            }
        } catch (\Exception $e) {
            return redirect()->route('items.list')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Export a listing of the resource.
     *
     * @author Thura Win
     * @create 30/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return Excel File.
     */
    public function exportSearchItems(Request $request, $id = FALSE)
    {
        try {

            if ($id) { // if there is id
                $exportItems = $this->itemInterface->getItemById($id);

                if (!$exportItems) { // if Errors
                    return redirect()->back()->withErrors(['id' => $id, 'message' => $exportItems]);
                }
                $filteredItems = collect([$exportItems])->map(function ($item) {
                    $filteredItem = new stdClass();
                    $filteredItem->item_id = $item->item_id;
                    $filteredItem->item_code = $item->item_code;
                    $filteredItem->item_name = $item->item_name;
                    $filteredItem->name = $item->name;
                    $filteredItem->safety_stock = $item->safety_stock;
                    $filteredItem->received_date = $item->received_date;
                    $filteredItem->description = $item->description;

                    return $filteredItem;
                });

                if ($request->type  == 'excel') { // if the type is excel
                    return Excel::download(new ItemsExport($filteredItems), 'items.xlsx');
                } else if ($request->type == 'pdf') { // if the type is pdf
                    $result = new PDFDownload($filteredItems);
                    return $result->downloadItemsAsPDF();
                }
            } else {
                $searchItems = $this->itemInterface->searchItems($request); // search items
                $searchResult = $searchItems->get();
                if (!$searchResult) { // if Errors
                    return redirect()->back()->with(['items' => $searchResult]);
                }

                $filteredItems = $searchResult->map(function ($item) {
                    $filteredItem = new stdClass();
                    $filteredItem->item_id = $item->item_id;
                    $filteredItem->item_code = $item->item_code;
                    $filteredItem->item_name = $item->item_name;
                    $filteredItem->name = $item->name;
                    $filteredItem->safety_stock = $item->safety_stock;
                    $filteredItem->received_date = $item->received_date;
                    $filteredItem->description = $item->description;

                    return $filteredItem;
                });

                if ($request->type  == 'excel') { // if the type is excel
                    return Excel::download(new ItemsExport($filteredItems), 'items.xlsx');
                } else if ($request->type == 'pdf') { // if the type is pdf
                    $result = new PDFDownload($filteredItems);
                    return $result->downloadItemsAsPDF();
                }
            }

            return redirect()->back()->with('items', $searchResult)->with('search', true)->with('id', $id);
        } catch (\Exception $e) {
            return redirect()->route('items.list')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Import a listing of the resource.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excelImport(ExcelImportRequest $request)
    {
        try {
            $file = $request->file('filExcel');
            Excel::import(new ItemRegisterExcelImport($this->itemInterface, $this->categoryInterface), $file);

            return redirect()->route('items.excel-form')->with(['success' => __('public.itemSaveSuccessfully')]);
        } catch (\Exception $e) {
            return redirect()->route('items.excel-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * suggest items for autocomplete.
     *
     * @author Thura Win
     * @create 03/07/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autoComplete(Request $request)
    {
        try {
            $suggestItems = $this->itemInterface->getItemId();
            return response()->json($suggestItems);
        } catch (\Exception $e) {
            return redirect()->route('items.excel-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * suggest items for autocomplete.
     *
     * @author Thura Win
     * @create 03/07/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchItemDetails(Request $request)
    {
        try {
            $itemId = $request->input('item_id');
            $suggestItems = $this->itemInterface->fetchDetails($itemId);
            if ($suggestItems) {
                $data = [
                    'code' => $suggestItems->item_code,
                    'name' => $suggestItems->item_name,
                    'category' => $suggestItems->category_id,
                ];
            } else {
                $data = [
                    'code' => '',
                    'name' => ''
                ];
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return redirect()->route('items.excel-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Activate Items
     *
     * @author Thura Win
     * @create 28/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemActive($id)
    {
        try {
            $isDeleted = $this->itemInterface->getItemById($id);
            if ($isDeleted == null) {
                return redirect()->back()->withErrors(['message' => __('public.itemIdLost')]);
            } elseif ($isDeleted->deleted_at == null) {
                return redirect()->back()->withErrors(['message' => __('public.itemAlreadyActive')]);
            }

            $isInactive = new ActiveItem($id);
            $isInactive->executeProcess();

            if (!$isInactive) {
                return response()->back()->withErrors(['message' => $isInactive]);
            }
            return redirect()->back()->with(['success' => __('public.itemActivated')]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Inactive Items
     *
     * @author Thura Win
     * @create 28/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemInactive($id)
    {
        try {

            $isDeleted = $this->itemInterface->getItemById($id);
            if ($isDeleted == null) {
                return redirect()->back()->withErrors(['message' => __('public.itemIdLost')]);
            } elseif ($isDeleted->deleted_at != null) {
                return redirect()->back()->withErrors(['message' => __('public.itemAlreadyInactive')]);
            }
            $isActive = new InactiveItem($id);

            $isDeleted = $this->itemInterface->getItemById($id);
            $isActive->executeProcess();

            if (!$isActive) {
                return response()->back()->withErrors(['message' => $isActive]);
            }
            return redirect()->back()->with(['success' => __('public.itemInactivated')]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Get All Active Items.
     * @author Thura Win
     * @create 12/07/2023
     * @return array
     */
    public function getActiveItems()
    {
        try {
            $activeItems = $this->itemInterface->getActiveItems();

            return $activeItems;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Get All Active Items.
     * @author Thura Win
     * @create 12/07/2023
     * @return array
     */
    public function getInactiveItems()
    {
        try {
            $inActiveItems = $this->itemInterface->getInactiveItems();

            return $inActiveItems;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author Thura Win
     * @create 23/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRegisterRequest $request)
    {
        //
        try {
            $item_id = $this->itemInterface->getMaxItemId();
            $newItem = new SaveItem($request, $item_id);

            $result = $newItem->executeProcess();
            if (!$result) {
                return redirect()->route('items.register-form')->withErrors(['message' => $result]);
            }

            if ($request->hasFile('filImage')) { // File field is not empty


                $newItemsUpload = new SaveItemUpload($request, $result['primary_key']);

                $uploadResult = $newItemsUpload->executeProcess();

                if (!$uploadResult) {
                    return redirect()->route('items.register-form')->withErrors(['message' => __('public.errorSavingItemImage')]);
                }
            }

            if ($item_id != $request->txtItemID) {
                return redirect()->route('items.register-form')->with(['success' => __('public.itemCreated') . ". ID \"" . $item_id . "\""]);
            }
            return redirect()->route('items.register-form')->with(['success' => __('public.itemCreated')]);
        } catch (\Exception $e) {
            return redirect()->route('items.register-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $showItem = $this->itemInterface->getItemById($id);
            $categories = $this->categoryInterface->getAllCategories();


            if (!$showItem) { // If item not found
                return redirect(Session::get('requestReferrer'))->withErrors(['message' => __('public.itemIdLost')]);
            } elseif ($showItem->deleted_at != null && request()->route()->getName() != 'items.detail') { // If item is inactive and the page is not detail page
                return redirect(Session::get('requestReferrer'))->withErrors(['message' => __('public.itemInactiveTryAgain')]);
            }


            if (request()->route()->getName() === 'items.detail') { // If the page is detail page
                return view('pages.detail')->with(['item' => $showItem->toArray()]);
            } elseif (request()->route()->getName() === 'items.update') { // If the page is update page
                return view('pages.update')->with(['item' => $showItem->toArray()])->with(['categories' => $categories]);
            }

            return redirect()->route('items.list');
        } catch (\Exception $e) {
            return redirect()->route('items.register-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRegisterRequest $request, $id)
    {
        //
        try {

            $isDeleted = $this->itemInterface->getItemById($id);
            if ($isDeleted == null) { // If item not found
                return redirect(Session::get('requestReferrer'))->withErrors(['message' => __('public.itemIdLost')]);
            } elseif ($isDeleted->deleted_at != null) {
                return redirect(Session::get('requestReferrer'))->withErrors(['message' => __('public.itemInactiveTryAgain')]);
            }

            $updateItem = new UpdateItem($request, $id);

            $result = $updateItem->executeProcess();
            if (!$result) { // If error 
                return redirect()->route('items.update', ['id' => $id])->withErrors(['message' => $result]);
            }
            if ($request->hasFile('filImage')) { // File field is not empty

                $isItemUploadExists = $this->itemsUploadInterface->CheckItemUploadExist($id);

                if (!count($isItemUploadExists) > 0) { // If image isn't already exists
                    $newItemsUpload = new SaveItemUpload($request, $result['primary_key']);

                    $uploadResult = $newItemsUpload->executeProcess();

                    if (!$uploadResult) { // If image not saved
                        return redirect()->route('items.update', ['id' => $id])->withErrors(['message' => __('public.errorSavingItemImage')]);
                    }
                } else { // If image already exists
                    $updateItemsUpload = new UpdateItemUpload($request, $id, $result['primary_key']);

                    $uploadResult = $updateItemsUpload->executeProcess();
                    if (!$uploadResult) { // If image not updated
                        return redirect()->route('items.update', ['id' => $id])->withErrors(['message' => __('public.errorSavingItemImage')]);
                    }

                    unlink($isItemUploadExists[0]->file_path); // Remove old image
                }
            } elseif ($request->remove_image) { // If remove image button is clicked on view
                $isItemUploadExists = $this->itemsUploadInterface->CheckItemUploadExist($id);

                if (count($isItemUploadExists) > 0) { // If image already exists

                    $deleteItemUpload = new DeleteItemUpload($result['primary_key']);

                    $isItemUploadDeleted = $deleteItemUpload->executeProcess();

                    if (!$isItemUploadDeleted) { // If image not deleted
                        return redirect()->back()->withErrors(['message' => $isItemUploadDeleted]);
                    }
                }
            }


            return redirect(Session::get('requestReferrer'))->with(['success' => __('public.itemUpdatedSuccessfully')]);
        } catch (\Exception $e) { // If error 
            return redirect()->route('items.update', ['id' => $id])->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author Thura Win
     * @create 30/06/2023
     * @param  \App\Models\Item  $item,Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemDeleteRequest $request)
    {
        //
        try {
            $isItemActive = $this->itemInterface->getItemById($request->txtId);
            if ($isItemActive->deleted_at != null) {
                return redirect()->back()->withErrors(['message' => __('public.itemInactiveTryAgain')]);
            }

            $isItemUploadExists = $this->itemsUploadInterface->CheckItemUploadExist($request->txtId);

            if (count($isItemUploadExists) > 0) {
                $deleteItemUpload = new DeleteItemUpload($request->txtId);

                $isItemUploadDeleted = $deleteItemUpload->executeProcess();

                if (!$isItemUploadDeleted) {
                    return redirect()->back()->withErrors(['message' => $isItemUploadDeleted]);
                }
            }

            $deleteItem = new DeleteItem($request->txtId);

            $isDeleted = $deleteItem->executeProcess();

            if (!$isDeleted) {
                return redirect()->back()->withErrors(['message' => $isDeleted]);
            }
            return redirect()->back()->with(['id' => $request->txtId, 'success' => __('public.itemDeletedSuccessfully')]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
