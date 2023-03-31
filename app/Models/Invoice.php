<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['member_id' , 'total_item' , 'total_transaction' , 'payment'];

    public function member (){
        return $this->belongsTo('App\Models\Member' , 'member_id');
    }

    public function sales (){
        return $this->hasMany('App\Models\Invoice' , 'invoice_id');
    }

    public function carts (){
        return $this->hasMany('App\Models\Invoice' , 'invoice_id');
    }
}
