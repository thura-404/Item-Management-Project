<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Interfaces\ItemInterface;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\CategoryInterface;
use App\DBTransactions\Items\SaveItem;
use App\Exports\ItemRegiserFormatExport;
use App\Http\Requests\ItemSearchRequest;
use App\Imports\ItemRegisterExcelImport;
use App\Http\Requests\ExcelImportRequest;
use App\DBTransactions\Items\InactiveItem;
use App\Http\Requests\ItemRegisterRequest;
use App\DBTransactions\ItemsUploads\SaveItemUpload;

class ItemController extends Controller
{
    private $itemInterface, $categoryInterface;

    public function __construct(Item $item, ItemInterface $itemInterface, CategoryInterface $categoryInterface)
    {
        $this->itemInterface = $itemInterface;
        $this->categoryInterface = $categoryInterface;
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
    public function search(ItemSearchRequest $request)
    {
        //
        try {
            $items = $this->itemInterface->searchItems($request); // search items
            return view('pages.index')->with('items', $items);
        } catch (\Exception $th) {
            //throw $th;
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
     * @create 23/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemActive($id)
    {
        try {
            return redirect()->route('items.excel-form')->with(['success' => 'Items saved successfully']);
        } catch (\Exception $e) {
            return redirect()->route('items.excel-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Inactive Items
     *
     * @author Thura Win
     * @create 23/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemInactive($id)
    {
        try {
            Log::info($id);
            $isInactive = new InactiveItem($id);
            $isInactive->executeProcess();

            if (!$isInactive) {
                return response()->back()->withErrors(['message' => $isInactive]);
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
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
