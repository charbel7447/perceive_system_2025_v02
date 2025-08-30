<?php

namespace App\StockMovement;

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
use App\ReceiveOrder\ReceiveOrder;
use App\ReceiveOrder\Item as ReceiveOrderItem;
use App\Product\Product;

class StockMovement extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'stock_movement';

    protected $search = [
        'product_code', 'product_name', 'purchase_order'
    ];

    protected $columns = [
        'id', 'code', 'description', 'unit_price','uom_id','qty','category_id','warehouse_id',
        'created_at','sub_category_id', 'minimum_stock','barcode','product_type','qty_on_hand'
    ];

    protected $fillable = [
        'description', 'unit_price', 'currency_id', 'uom_id','qty','category_id','warehouse_id'
        ,'sub_category_id', 'minimum_stock','barcode','product_type','qty_on_hand'
    ];


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class,'uom','id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }


}
