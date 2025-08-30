<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;

class ItemLog extends Model
{
    protected $table = 'bill_items_log';

    protected $fillable = [
        'product_id', 'qty', 'unit_price', 'vendor_reference','uom_code','tax_name','tax_rate','product_name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }

    public function uom()
    {
        // return $this->hasMany(Uom::class, 'id','uom_id');
        return $this->hasMany(Uom::class, 'name','uom_code');
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
