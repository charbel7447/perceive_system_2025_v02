<?php

namespace App\ProductsAggregation;

use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\Currency;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Product\Product;
use App\Uom;

class Item extends Model {

    protected $table = 'product_aggregation_items';

    use Search;
    use HasManyRelation;

    protected $search = [
        'price'
    ];
    protected $appends = [
        'text'
    ];

    protected $fillable = [
        'product_id','product_code','product_name' ,'product_price','current_stock', 'uom_id','product_aggregation_id'
        // ,'product_aggregation_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }
    

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function vendor1()
    {
        return $this->hasMany(Vendor::class,'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['product_id'];
    }
}
