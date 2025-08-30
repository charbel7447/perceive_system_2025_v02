<?php

namespace App\ContainerOrderReport;

use Illuminate\Database\Eloquent\Model;
// use App\Product\Product;
// use App\Product\Item as ProductItem;

use App\Product\Product as ProductItem;
use App\Product\Item as Products;
use App\Uom;
use App\Vendor;
use App\Employee;
use App\Shipper;

use App\Currency;
use App\ContainerOrder\ContainerOrder;

class Item extends Model
{
    protected $table = 'container_order_report_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'vendor_id',
        'created_by',
        'from_date',
        'qty_received',
        'to_date',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function product()
    {
        return $this->belongsTo(ProductItem::class, 'product_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_request_id', 'id');
    }

    public function container_orders()
    {
        return $this->belongsTo(ContainerOrder::class, 'container_order_id', 'id');
    }

    public function shippers()
    {
        return $this->belongsTo(Shipper::class,'shipper_id', 'id');
    }


    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class);
    // }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }

    
    // public function uom()
    // {
    //     return $this->hasMany(Uom::class, 'uom_id','id');
    // }
}
