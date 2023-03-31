<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id' , 'product_id' , 'qty' , 'product_price' , 'subtotal'];


    public function invoice (){
        return $this->belongsTo('App\Models\Invoice' , 'invoice_id');
    }

    public function products (){
        return $this->belongsTo('App\Models\Product' , 'product_id');
    }
}
