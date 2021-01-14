<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    protected  $table='images';
    protected $fillable = ['product_id','photo'];



}
