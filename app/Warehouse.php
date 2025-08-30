<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\Uom;
use App\Vendor;
use App\ReceiveOrder\ReceiveOrder;

class Warehouse extends Model
{
    use Search;
    protected $table = 'warehouses';
    protected $connection = "mysql";
    
    protected $search = [
        'name', 'number', 'description', 'created_by'
    ];

    protected $columns = [
        'id', 'name', 'number', 'description', 'created_by','created_at'
    ];

    protected $fillable = [
        'id', 'name', 'number', 'description', 'created_by','created_at'
    ];

    protected $appends = [
        'text'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'warehouse_id', 'id');
    }

    // public function items()
    // {
    //     return $this->belongsTo(Items::class, 'warehouse_id', 'id');
    // }

    public function productd()
    {
        return $this->belongsTo(Product::class,'item_id','id');
    }
    

    public function items()
    {
        return $this->belongsTo(Product::class, 'warehouse_id', 'id');
    }
    
    public function receiveoorder()
    {
        return $this->belongsTo(ReceiveOrder::class,'id','receive_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class,'unit', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function getTextAttribute()
    {
        if(is_null($this->attributes['name'])) {
            return $this->attributes['number'];
        }

        return $this->attributes['number'] .' - '. $this->attributes['name'];;
    }

}
