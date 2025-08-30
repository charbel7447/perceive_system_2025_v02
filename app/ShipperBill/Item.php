<?php

namespace App\ShipperBill;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;

class Item extends Model
{
    protected $table = 'shipper_bill_items';

    protected $fillable = [
        'product_id', 'quantity', 'unit_price', 'vendor_reference','uom_id','tax_name','tax_rate'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function shipperbill()
    {
        return $this->belongsTo(ShipperBill::class, 'shipper_bill_id', 'id');
    }

    public function uom()
    {
        // return $this->hasMany(Uom::class, 'id','uom_id');
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function uomd()
    {
        // return $this->belongsTo(Uom::class, 'uom_id','id');
        // return $this->belongsTo(Uom::class, 'uom_code','name');
        return $this->hasMany(Uom::class, 'id','uom_id');
    }
    public function productd()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    
}
