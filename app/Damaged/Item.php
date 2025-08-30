<?php

namespace App\Damaged;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;

class Item extends Model
{
    protected $table = 'damaged_items';

    protected $fillable = [
       'id','product_id','transfer_qty','uom_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function uom1()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }
  

    public function uom()
    {
        return $this->hasMany(Uom::class, 'id','uom_id');
    }
}
