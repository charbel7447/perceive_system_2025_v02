<?php

namespace App\FinishedProduct;

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
use App\FinishedProduct\Attribute as FPAttributes;
use App\Product\Product;

class FinishedProduct extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'finished_product';

    protected $search = [
        'code', 'description', 'minimum_stock','qty_on_hand'
    ];

    protected $columns = [
        'id', 'code', 'description', 'unit_price','uom_id','qty','type_id','comment',
        'created_at', 'minimum_stock','barcode','product_type','qty_on_hand','warehouse'
        // ,'uploaded_logo','uploaded_logo_file'
    ];

    protected $fillable = [
        'description', 'unit_price', 'currency_id', 'uom_id','qty','type_id','comment'
        , 'minimum_stock','barcode','product_type','qty_on_hand','warehouse'
        // ,'uploaded_logo','uploaded_logo_file'
    ];

    protected $appends = [
        'text'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function uom1()
    {
        return $this->hasMany(Uom::class,'id', 'uom_id');
    }
    
    
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }



    public function items()
    {
        return $this->hasMany(Item::class, 'product_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'product_id', 'id');
    }


    public function product()
    {
        return $this->hasMany(Item::class, 'product_id', 'id');
    }

    public function vendor1()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }

    // public function vendor()
    // {
    //     return $this->hasMany(Vendor::class,);
    // }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    
    
    public function values1()
    {
        return $this->belongsTo(Attributes::class, 'attribute_id', 'id');
    }
    
    public function values()
    {
        return $this->hasMany(FPAttributes::class, 'product_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(FPAttributes::class, 'product_id', 'id');
    }

    public function machines()
    {
        return $this->hasMany(Machines::class, 'product_id', 'id');
    }

    public function settings()
    {
        return $this->hasMany(MachinesSettings::class, 'product_id', 'id');
    }
 

    // public function attribute()
    // {
    //     return $this->hasMany(FPAttributes::class, 'product_id', 'id');
    // }

    // public function Attribute()
    // {
    //     return $this->belongsTo(FPAttributes::class, 'product_id', 'id');
    // }
     
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }


    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'product_id', 'id');
    }

    
    public function uomd()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function categoryd()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_categoryd()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }


    public function warehoused()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }


    public function getTextAttribute()
    {
        return $this->attributes['generated_code'] .' - '. $this->attributes['description'];
    }
}
