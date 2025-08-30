<?php

namespace App\WarehouseReportCriteria;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Currency;
use App\Warehouse;
use App\Uom;
use App\Vendor;
use App\Product\Product as ProductItem;
use App\Product\Item as Products;

class Item extends Model
{
    protected $table = 'warehouses_report_criteria_items';

    protected $fillable = [
        'report_id', 'warehouse_id','product_id','vendor_id','from_date','to_date','uom_id','category_id'
    ];

    public function items()
    {
        return $this->belongsTo(Products::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductItem::class, 'product_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class,'unit','id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

}
