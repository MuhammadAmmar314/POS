<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $productCategory = ProductCategory::count();
        $product = Product::count();
        $sales = Invoice::sum('total_transaction');
        $member = Member::count();

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $data_tanggal = array();
        $data_sales = array();

        while (strtotime($start_date) <= strtotime($end_date)) {
            $data_tanggal[] = (int) substr($start_date, 8, 2);

            $total_sales = Invoice::where('created_at', 'LIKE', "%$start_date%")->sum('total_transaction');
        //     $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
        //     $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

        //     $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_sales[] += $total_sales;

            $start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
        }

        $start_date = date('Y-m-01');

        return view('home' , compact('productCategory' , 'product' , 'member' , 'sales' , 'start_date' , 'end_date' , 'total_sales' , 'data_tanggal' , 'data_sales'));
    }
}
