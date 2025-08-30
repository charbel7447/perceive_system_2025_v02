<?php

namespace App\ReceiveOrderReport;

use Illuminate\Database\Eloquent\Model;
// use App\Product\Product;
// use App\Product\Item as ProductItem;

use App\Product\Product as ProductItem;
use App\Product\Item as Products;
use App\Uom;
use App\Vendor;
use App\Employee;
use App\Currency;
use App\PurchaseOrder\PurchaseOrder;
use App\ReceiveOrder\ReceiveOrder;

class Item extends Model
{
    protected $table = 'receive_orders_report_items';

    protected $fillable = [
        'user_id',
        'report_id',
        'category_id',
        'product_id',
        'vendor_id',
        'created_by',
        'from_date',
        'purchase_order_item_id',
        'received_qty',
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

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function receive_order()
    {
        return $this->belongsTo(ReceiveOrder::class, 'receive_order_id', 'id');
    }
    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class);
    // }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
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
