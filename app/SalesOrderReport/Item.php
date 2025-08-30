<?php

namespace App\SalesOrderReport;

use Illuminate\Database\Eloquent\Model;
// use App\Product\Product;
// use App\Product\Item as ProductItem;

use App\Product\Product as ProductItem;
use App\Product\Item as Products;
use App\Uom;
use App\Client;
use App\Currency;
use App\SalesOrder\SalesOrder;


class Item extends Model
{
    protected $table = 'sales_order_report_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'client_id',
        'created_by',
        'from_date',
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

    public function sales_orders()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }
    
    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }


    public function clients()
    {
        return $this->belongsTo(Client::class,'client_id', 'id');
    }


}
