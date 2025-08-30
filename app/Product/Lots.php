<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Uom;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Warehouse;
use App\Category;
use App\SubCategory;
use App\Vendor;
use App\StockCount\StockCountLot;

class Lots extends Model {

    protected $connection = "mysql";
    protected $table = 'products_lots';

    use Search;
    use HasManyRelation;

    protected $search = [
        'code','product_name'
    ];

     protected $columns = [
        'id',
        'code',
        'product_name',
        'qty',
        'category_id',
        'sub_category_id',
        'vendor_id',
        'uom_id',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'code',
        'product_name',
        'qty',
        'category_id',
        'sub_category_id',
        'vendor_id',
        'uom_id',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }

    public function receive_order()
    {
        return $this->belongsTo(\App\ReceiveOrder\ReceiveOrder::class, 'receive_order_id', 'id');
    }

        public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    
        public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_categoryid', 'id');
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_categoryid', 'id');
    }

    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

        public function stockCounts()
    {
        return $this->hasMany(StockCountLot::class, 'lot_id');
    }



    public function getTextAttribute()
    {
        return $this->attributes['code'];
    }
}
