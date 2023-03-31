<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('product_name')->get();
        $members = Member::orderBy('member_name')->get();

        if ($invoice_id = session('id')) {
            $invoice = Invoice::find($invoice_id);
            $memberSelected = $invoice->member_id ?? new Member();

            
            return view('admin.cart.cart' , compact('products' , 'members' , 'invoice' , 'invoice_id' , 'memberSelected'));
        }
    }

    public function api($id)
    {
        $carts = Cart::with('products')->where('invoice_id' , $id)->get();

        $data = array();
        $total_transaction = 0;
        $total_item = 0;


        foreach ($carts as $item) {
            $row = array();
            $row['product_id'] = $item->product_id;
            $row['product_name'] = $item->products['product_name'];
            $row['product_price']  = 'Rp. '. format_uang($item->product_price);
            $row['qty']      = "<input type='number' class='form-control input-sm quantity' style='width: 80px' data-id='". $item->id ."' value='". $item->qty ."'>";
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = "<div class='btn-group'><button onclick='vm.deleteData(`". route("carts.destroy", $item->id) ."`)' class='btn btn-xs btn-danger btn-flat'><i class='fa fa-trash'></i></button></div>";
            $data[] = $row;

            $total_transaction += $item->product_price * $item->qty;
            $total_item += $item->qty;
        }
        $data[] = [
            'product_id'     => "<div class='total_transaction d-none'>". $total_transaction ."</div> <div class='total_item d-none'>". $total_item ."</div>",
            'product_name'   => '',
            'product_price'  => '',
            'qty'            => '',
            'subtotal'       => '',
            'aksi'           => '',
        ];

        $datatables = datatables()->of($data)->addIndexColumn()->rawColumns(['qty' , 'aksi' , 'product_id']);   
        
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
        $product = Product::where('id', $request->product_id)->first();
        if (! $product) {
            return response()->json('Data gagal disimpan', 400);
        }
        
        $detail = new Cart();
        $detail->invoice_id = $request->invoice_id;
        $detail->product_id = $product->id;
        $detail->product_price = $product->product_price;
        $detail->qty = 1;
        $detail->subtotal =$product->product_price;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        $cart->qty = $request->qty;
        $cart->subtotal = $cart->product_price * $request->qty;
        $cart->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();

        return back();
    }

    public function loadForm($total_transaction = 0, $payment = 0)
    {
        $kembali = ($payment != 0) ? $payment - $total_transaction : 0;
        $data    = [
            'totalrp' => format_uang($total_transaction),
            'terbilang' => ucwords(terbilang($total_transaction). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
