<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.invoices.index');
    }

    public function api()
    {
        $invoices = Invoice::with('member')->orderBy('created_at' , 'desc')->get();
        // $invoices = Invoice::join('members' , 'members.id' , '=' , 'invoices.member_id')
        //                     ->select('invoices.id' , 'invoices.created_at' , 'invoices.member_id' , 'members.member_name' , 'total_item' , 'total_transaction')
        //                     ->orderBy('invoices.created_at' , 'desc')
        //                     ->get();
        // return $invoices;

        return datatables()
            ->of($invoices)
            ->addIndexColumn()
            ->addColumn('member_name' , function($invoices){
                return $invoices->member->member_name ?? "";
            })
            ->editColumn('total_transaction', function ($invoices) {
                return 'Rp. '. format_uang($invoices->total_transaction) .',-';
            })
            ->addColumn('transaction_date', function ($invoices) {
                // $date = Carbon::parse($invoices->created_at);
                // return $date->format('d M Y');
                return tanggal_indonesia($invoices->created_at, false);
            })
            ->addColumn('action', function ($invoices) {
                return "
                <div class='btn-group'>
                    <button onclick='vm.showDetail(`". route("invoices.show", $invoices->id) ."`)' class='btn btn-xs btn-info btn-flat'><i class='fa fa-eye'></i></button>
                    <button onclick='vm.deleteData(`". route("invoices.destroy", $invoices->id) ."`)' class='btn btn-xs btn-danger btn-flat'><i class='fa fa-trash'></i></button>
                </div>
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $invoice = new Invoice();
        $invoice->member_id = null;
        $invoice->total_item = 0;
        $invoice->total_transaction = 0;
        $invoice->payment = 0;
        $invoice->save();

        session(['id' => $invoice->id]);
        return redirect()->route('carts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->id = $request->id;
        $invoice->member_id = $request->member_id;
        $invoice->total_item = $request->total_item;
        $invoice->total_transaction = $request->total_transaction;
        $invoice->payment = $request->payment;
        $invoice->update();

        $cart = Cart::where('invoice_id', $invoice->id)->get();
        foreach ($cart as $item) {
            $product = Product::find($item->product_id);
            $product->product_quantity -= $item->qty;
            $product->update();
        }

        return view('admin.invoices.succeed');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carts = Cart::with('products')->where('invoice_id', $id)->get();

        return datatables()
            ->of($carts)
            ->addIndexColumn()
            ->addColumn('product_name', function ($carts) {
                return $carts->products->product_name;
            })
            ->editColumn('product_price', function ($carts) {
                return 'Rp. '. format_uang($carts->product_price);
            })
            ->editColumn('subtotal', function ($carts) {
                return 'Rp. '. format_uang($carts->subtotal);
            })
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $carts    = Cart::where('invoice_id', $invoice->id)->get();
        
        foreach ($carts as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->product_quantity += $item->qty;
                $product->update();
            }

            $item->delete();
        }

        $invoice->delete();

        return redirect()->back();
    }
}
