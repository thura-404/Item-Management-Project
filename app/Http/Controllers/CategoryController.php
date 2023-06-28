<?php

namespace App\Http\Controllers;

use App\DBTransactions\Categories\DeleteCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CategoryInterface;
use App\Http\Requests\CategoryAddRequest;
use Illuminate\Support\Facades\Validator;
use App\DBTransactions\Categories\SaveCategory;
use App\Http\Requests\DeleteCategoryRequest;

/**
 * Class CategoryController
 * @author Thura Win
 * @create 22/06/2023
 * @return array
 */
class CategoryController extends Controller
{

    private $categoryInterface;

    public function __construct(CategoryInterface $categoryInterface)
    {
        $this->categoryInterface = $categoryInterface;
    }


    /**
     * Display a listing of the resource.
     * 
     * @author Thura Win
     * @create 22/06/2023
     * @return array
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->categoryInterface->getAllCategories();
    }

    /**
     * Display a listing of the resource.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @return \Illuminate\Http\Response
     */
    public function getDeletableCategories()
    {
        //
        $deletableCategories = $this->categoryInterface->getUnusedCategories();
        if (count($deletableCategories) == 0) {
            return response()->json(['html' => '<option value="" disabled selected hidden>No Deletable Categories</option>']);
        }

        $html = '<option value="" disabled selected hidden>Choose Category to Delete</option>';
        foreach ($deletableCategories as $category) {
            $html .= '<option value="' . $category->id . '">' . $category->name . '</option>';
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @author Thura Win
     * @create 22/06/2023
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryAddRequest $request)
    {
        //
        try {

            $newClass = new SaveCategory($request);
            $result = $newClass->executeProcess();

            if (!$result) {
                return redirect()->route('items.register-form')->withErrors(['message' => 'Error saving Category']);
            }

            $allCategories = $this->categoryInterface->getAllCategories();

            return $allCategories;
        } catch (\Exception $e) {
            return redirect()->route('items.register-form')->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author Thura Win
     * @create 22/06/2023
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, DeleteCategoryRequest $request)
    {
        //
        try {
            $id = $request->id;
            $classExist = $this->categoryInterface->getCategoryById($id);

            if (!$classExist) {
                return redirect()->route('items.register-form')->withErrors(['message' => 'Category Not Found']);
            }

            $deleteCategory = new DeleteCategory($id);

            $result = $deleteCategory->executeProcess();


            if (!$result) {
                return redirect()->route('items.register-form')->withErrors(['message' => 'Error deleting Category']);
            }

            $allCategories = $this->categoryInterface->getAllCategories();

            return $allCategories;
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
