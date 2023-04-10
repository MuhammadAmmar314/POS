<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category_id' , 'unit_id' , 'product_name' , 'product_quantity' , 'product_cost' , 'product_price'];

    public function product_category (){
        return $this->belongsTo('App\Models\ProductCategory' , 'category_id');
    }

    public function product_unit (){
        return $this->belongsTo('App\Models\ProductUnit' , 'unit_id');
    }

    public function carts (){
        return $this->hasMany('App\Models\Cart' , 'product_id');
    }

    public function invoice_details (){
        return $this->hasMany('App\Models\InvoiceDetail' , 'product_id');
    }
}
