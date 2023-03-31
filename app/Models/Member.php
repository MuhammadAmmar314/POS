<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['member_name' , 'member_phone' , 'address'];

    public function invoices(){
        return $this->hasMany('App\Models\Invoice' , 'member_id');
    }
}
