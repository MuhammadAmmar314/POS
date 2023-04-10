<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['member_id' , 'total_item' , 'total_transaction' , 'payment' , 'user_id'];

    public function member (){
        return $this->belongsTo('App\Models\Member' , 'member_id');
    }

    public function user (){
        return $this->belongsTo('App\Models\User' , 'user_id');
    }

    public function invoice_details (){
        return $this->hasMany('App\Models\Invoice' , 'invoice_id');
    }
}
