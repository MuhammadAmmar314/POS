<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $product_categories = ProductCategory::all();
        $product_units = ProductUnit::all();

        return view('admin.product' , compact('product_categories' , 'product_units'));
    }

    public function api(Request $request)
    {
        if($request->category) {
            $products = Product::where('category_id' , $request->category)->get();
        } else {
            $products = Product::all();
        }
        
        return json_encode($products);
        
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
            'category_id' => ['required'],
            'unit_id' => ['required'],
            'product_name' => ['required'],
            'product_quantity' => ['required' , 'numeric'],
            'product_cost' => ['required' , 'numeric'],
            'product_price' => ['required' , 'numeric'],
        ]);

        Product::create($request->all());
        
        return redirect('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'category_id' => ['required'],
            'unit_id' => ['required'],
            'product_name' => ['required'],
            'product_quantity' => ['required' , 'numeric'],
            'product_cost' => ['required' , 'numeric'],
            'product_price' => ['required' , 'numeric'],
        ]);

        $product->update($request->all());

        return response(redirect('products'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
