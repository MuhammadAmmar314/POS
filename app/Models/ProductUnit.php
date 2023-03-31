<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = ['unit_name'];

    public function products(){
        return $this->hasMany('App\Models\Product' , 'unit_id');
    }
}
