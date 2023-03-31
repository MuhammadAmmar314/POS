<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Http\Request;

class ProductUnitController extends Controller
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
        return view('admin.product_unit');
    }

    public function api()
    {
        $productUnits = ProductUnit::all();
        
        $datatables = datatables()->of($productUnits)->addIndexColumn();
        
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
            'unit_name' => ['required' , 'string' , 'min:5'],
        ]);

        ProductUnit::create($request->all());

        return redirect(('product_units'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductUnit $productUnit)
    {
        $this->validate($request, [
            'unit_name' => ['required' , 'string' , 'min:5'],
        ]);
        
        $productUnit->update($request->all());

        return redirect(('product_units'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductUnit $productUnit)
    {
        $productUnit->delete();
    }
}
