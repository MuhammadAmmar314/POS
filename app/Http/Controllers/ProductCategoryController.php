<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.product_category');
    }

    public function api()
    {
        $productCategories = ProductCategory::all();
        
        $datatables = datatables()->of($productCategories)->addIndexColumn();
        
        return $datatables->make(true);
        
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_name' => ['required' , 'string' , 'min:5'],
        ]);

        ProductCategory::create($request->all());

        return redirect(('product_categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $this->validate($request, [
            'category_name' => ['required' , 'string' , 'min:5'],
        ]);
        
        $productCategory->update($request->all());

        return redirect(('product_categories'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
    }
}
