<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.invoices.index');
    }

    public function api()
    {
        $invoices = Invoice::with('member')->orderBy('created_at' , 'desc')->get();

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
        if (session()->has('id')){
            return redirect()->route('carts.index');
        } else {
            session(['id' => Str::random()]);
            return redirect()->route('carts.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->code == session('id')){
            $carts = Cart::where('user_id' , '=' , $request->input('user_id'))->get();

            $invoice = Invoice::create([
                'member_id' => $request->input('member_id'),
                'total_item' => $request->input('total_item'),
                'total_transaction' => $request->input('total_transaction'),
                'payment' => $request->input('payment'),
                'user_id' => $request->input('user_id'),
            ]);
            foreach ($carts as $item){
                $detail = InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'product_price' => $item->product_price,
                    'subtotal' => $item->subtotal,
                ]);

                $product = Product::find($item->product_id);
                $product->product_quantity -= $item->qty;
                $product->update();

                $item->delete();
            }
        }

        session()->pull('id');

        return view('admin.invoices.succeed');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detail = InvoiceDetail::with('products')->where('invoice_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_name', function ($detail) {
                return $detail->products->product_name;
            })
            ->editColumn('product_price', function ($detail) {
                return 'Rp. '. format_uang($detail->product_price);
            })
            ->editColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
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
        $detail    = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        
        foreach ($detail as $item) {
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
