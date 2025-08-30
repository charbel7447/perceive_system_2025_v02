<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Items\Items;
use App\Items\Item as ProductItem;
use App\Uom;
use App\Warehouse;
use App\Category;

class Transfers extends Model
{
    use Search;
    protected $table = 'transfers';

    protected $search = [
       'product_id', 'code', 'category_id', 'warehouse_id','created_by'
    ];

    protected $columns = [
        'id','qty_on_hand','to_warehouse_id','product_id', 'code', 'category_id', 'warehouse_id','created_by', 'vendor_id','description'
    ];

    protected $fillable = [
        'qty_on_hand','to_warehouse_id','product_id', 'code', 'category_id', 'warehouse_id','created_by', 'vendor_id','description'
    ];

    protected $appends = [
        'text'
    ];

    public function products()
    {
        return $this->hasMany(ProductItem::class, 'warehouse_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Items::class);
    }
    
    public function uom()
    {
        return $this->belongsTo(Uom::class,'uom_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['name'])) {
            return $this->attributes['number'];
        }

        return $this->attributes['number'] .' - '. $this->attributes['name'];;
    }

}
