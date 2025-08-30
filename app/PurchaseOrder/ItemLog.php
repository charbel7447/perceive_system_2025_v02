<?php

namespace App\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use App\Vendor;

class ItemLog extends Model
{
    protected $table = 'purchase_order_items_log';

    protected $fillable = [
        'product_id', 'qty', 'unit_price', 'vendor_reference','tax_name','tax_rate','uom_unit',
        'volume_box','ct_box','weight_box','vendor_id','uom_id','product_name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    // public function uom()
    // {
    //     return $this->hasMany(Uom::class, 'id','uom_id');
    // }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }
    
    // public function taxes()
    // {
    //     return $this->hasMany(Tax::class, 'purchase_order_item_id', 'id');
    // }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id','id');
    }

    public function uomd()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }
    public function productd()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    
}
