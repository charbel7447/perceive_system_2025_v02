<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;

class Conversion extends Model {

    protected $table = 'product_conversions';

    protected $fillable = [
         'base_qty', 'converted_qty','converted_uom_id','converted_uom_code','converted_uom_unit'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function converted_uom()
    {
        return $this->belongsTo(Uom::class, 'converted_uom_id', 'id');
    }
    
    
}
