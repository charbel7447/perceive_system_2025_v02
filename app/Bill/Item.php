<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use App\Bill\Tax;

class Item extends Model
{
    protected $table = 'bill_items';

    protected $fillable = [
        'product_id', 'qty', 'unit_price', 'vendor_reference','uom_id','uom_unit','uom_code','tax_name','tax_rate','product_name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'bill_item_id', 'id');
    }


    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function uomd()
    {
        // return $this->belongsTo(Uom::class, 'uom_id','id');
        return $this->belongsTo(Uom::class, 'uom_code','name');
    }
    public function productd()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    
}
