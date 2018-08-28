<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::parentCategories();
        $page = $categories->currentPage();

        if ($request->ajax()) {
            return view('admin.categories.content', compact([
                'categories',
                'page'
            ]));
        }

        return view('admin.categories.index', compact([
            'categories',
            'page'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::arrayCategories();

        return view('admin.categories.create', compact('categories'));
    }

    public function getFilePath(Request $request, $name)
    {
        $file = $request->file($name);
        $fileName = $file->getClientOriginalName();
        $filePath = config('filesystems.img_path');
        if (file_exists($file)) {
            $fileName = md5(str_random(15)) . $fileName;
        }
        $path = $file->storeAs(config('site.thumbnail') . $filePath, $fileName, 'option');

        return $path;
    }

    public function getValidateRule()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'image' => 'mimes:jpeg,png,jpg,bmp,svg|max:2048',
            'parent_id' => 'nullable|numeric',
        ];
    }

    public function saveCategory(Request $request, Category $category)
    {
        if ($request->has('image')) {
            $request->merge([
                'thumbnail' => $this->getFilePath($request, 'image')
            ]);
        }

        $category->saveCategory($request->all());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getValidateRule());

        $category = new Category();
        $this->saveCategory($request, $category);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::arrayCategories();

        return view('admin.categories.create', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidateRule());

        $category = Category::findOrFail($id);
        $this->saveCategory($request, $category);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $cate = Category::findOrFail($id);
            Product::deleteProductByCategory($id);
            Category::deleteSubCategory($id);
            $cate->deleteCategory();
        });

        return redirect()->route('admin.categories.index');
    }
}
