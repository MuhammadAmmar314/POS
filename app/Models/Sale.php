<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function invoice (){
        return $this->belongsTo('App\Models\Invoice' , 'invoice_id');
    }

    public function product (){
        return $this->belongsTo('App\Models\Product' , 'product_id');
    }
}
