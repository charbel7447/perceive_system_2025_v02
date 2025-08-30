<?php

namespace App\ProductsAggregation;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Uom;
use App\Warehouse;
use App\Category;
use App\SubCategory;
use App\Vendor;
use App\Attributes\Attributes;
use App\ProductsAggregation\Item;

class ProductsAggregation extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'products_aggregation';

    protected $search = [
        'code', 'description', 'minimum_stock','current_stock'
    ];

    protected $columns = [
        'id', 'code', 'description', 'unit_price','uom_id','qty','category_id','warehouse_id',
        'created_at','sub_category_id', 'minimum_stock','barcode','current_stock'
    ];

    protected $fillable = [
        'description', 'unit_price', 'currency_id', 'uom_id','qty','category_id','warehouse_id'
        ,'sub_category_id', 'minimum_stock','barcode','current_stock'
    ];

    protected $appends = [
        'text'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'product_aggregation_id', 'id');
    }


    public function product()
    {
        return $this->hasMany(Item::class, 'product_id', 'id');
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
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['code'] .' - '. $this->attributes['description'];
    }
}
