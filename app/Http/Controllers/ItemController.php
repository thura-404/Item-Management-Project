<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use App\Interfaces\ItemInterface;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\CategoryInterface;
use App\DBTransactions\Items\SaveItem;
use App\Interfaces\ItemUploadInterface;
use App\DBTransactions\Items\ActiveItem;
use App\DBTransactions\Items\DeleteItem;
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
        return view('pages.index')->with('items', $items)->with('categories', $categories);
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
            $searchResult = $this->itemInterface->searchItems($request); // search items
            $categories = $this->categoryInterface->getUsedCategories();
            if (!$searchResult) {
                return view('pages.index')->with(['items' => $searchResult])->with('categories', $categories);
            }
            $_SESSION['searchDownlaod'] = $searchResult;
            return view('pages.index')->with('items', $searchResult)->with('categories', $categories)->with('search', true);
        } catch (\Exception $e) {
            return view('pages.index')->withErrors(['message' => $e->getMessage()])->with('categories', $categories);
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
    public function exportAllItems($type)
    {
        try {
            if ($type == 'excel') {
                $itemsToDownload = $this->itemInterface->downloadItems();
                return Excel::download(new ItemsExport($itemsToDownload), 'items.xlsx');
            } else if ($type == 'pdf') {
                $itemsToDownload = $this->itemInterface->downloadItems();
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
    public function exportSearchItems(Request $request)
    {
        try {
            if ($request->type  == 'excel') {
                Log::info(unserialize(base64_decode($request->items)));
                return Excel::download(new ItemsExport(unserialize(base64_decode($request->input('items')))), 'items.xlsx');
            } else if ($request->type == 'pdf') {
                $result = new PDFDownload($request->items);
                return $result->downloadItemsAsPDF();
            }
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

            return redirect()->route('items.excel-form')->with(['success' => 'Items saved successfully']);
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
            Log::info($id);
            $isInactive = new ActiveItem($id);
            $isInactive->executeProcess();

            if (!$isInactive) {
                return response()->back()->withErrors(['message' => $isInactive]);
            }
            return redirect()->back()->with(['success' => 'Items Activated!']);
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
            Log::info($id);
            $isActive = new InactiveItem($id);
            $isActive->executeProcess();

            if (!$isActive) {
                return response()->back()->withErrors(['message' => $isActive]);
            }
            return redirect()->back()->with(['success' => 'Items Inactivated!']);
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
            $newItem = new SaveItem($request);

            $result = $newItem->executeProcess();
            if (!$result) {
                return redirect()->route('items.register-form')->withErrors(['message' => $result]);
            }

            if ($request->hasFile('filImage')) { // File field is not empty


                $newItemsUpload = new SaveItemUpload($request, $result['primary_key']);

                $uploadResult = $newItemsUpload->executeProcess();

                if (!$uploadResult) {
                    return redirect()->route('items.register-form')->withErrors(['message' => 'Error saving Item Image']);
                }
            }


            return redirect()->route('items.register-form')->with(['success' => 'Item created successfully']);
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
    public function show(Item $item, $id, $destinationPage, $sourcePage)
    {
        //
        try {
            $showItem = $this->itemInterface->getItemById($id);

            if (!$showItem) {
                return redirect()->route('items.register-form')->withErrors(['message' => $showItem]);
            }

            if ($destinationPage === 'detail') {
                return view('detail-page', ['item' => $showItem]);
            } elseif ($destinationPage === 'update') {
                return view('update-page', ['item' => $showItem]);
            } else {
                // Invalid page type, handle the error or redirect to an appropriate route
                return redirect()->route('items.register-form')->withErrors(['message' => 'Invalid page type']);
            }
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
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
            $isItemUploadExists = $this->itemsUploadInterface->CheckItemUploadExist($request->txtId);

            if(count($isItemUploadExists) > 0)
            {
                $deleteItemUpload = new DeleteItemUpload($request->txtId);

                $isItemUploadDeleted = $deleteItemUpload->executeProcess();

                if(!$isItemUploadDeleted)
                {
                    return redirect()->back()->withErrors(['message' => $isItemUploadDeleted]);
                }
            }

            $deleteItem = new DeleteItem($request->txtId);

            $isDeleted = $deleteItem->executeProcess();

            if (!$isDeleted) {
                return redirect()->back()->withErrors(['message' => $isDeleted]);
            }

            return redirect()->back()->with(['success' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
