<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Support\SearchProducts;

use App\Currency;
use App\Uom;
use App\Warehouse;
use App\Category;
use App\SubCategory;
use App\Vendor;
use App\Attributes\Attributes;
use App\RawMaterialType;
use App\SalesOrder\Item as SalesOrderItems;
use App\Brand;
use App\SubSubCategory;

class Product extends Model
{
    use Search;
    use SearchProducts;
    use HasManyRelation;

    protected $connection = "mysql";
    protected $table = 'products';

    const ACTIVE = 1;
    const INACTIVE = 2;
    
    protected $search = [
        'title','code', 'description', 'min_qty','current_stock' 
    ];


    protected $SearchProducts = [
        'title','code', 'description', 'min_qty','current_stock'
    ];

    protected $columns = [
        'id', 'code', 'description', 'unit_price','qty','category_id','warehouse_id','current_stock',
        'volume_box','ct_box','weight_box','warehouse_qty','tax_name','tax_rate','uom_id','unitprice',
        'created_at','sub_category_id', 'minimum_stock','barcode','product_type','current_stock','raw_material_type_id',
        'nb_boxes_1','nb_boxes_1_price','nb_boxes_2','nb_boxes_2_price','nb_boxes_3','nb_boxes_3_price','original_price',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6','sale_price','lot_qty',
        'field7',
        'field8',
        'field9',
        'field10','size','location','class_a_price','class_b_price','class_c_price','top_search','new','featured',
        'brand_id','item_box','upc_number','sub_sub_categoryid',
        'best_selling',
        'deal_of_the_day',
        'deal_date',
        'special_price','product_dropdown_1_id','product_dropdown_2_id'
    ];

    protected $fillable = [
        'id','code',
        'description', 'unit_price', 'currency_id','qty','category_id','warehouse_id','current_stock',
        'volume_box','ct_box','weight_box','warehouse_qty','tax_name','tax_rate','uom_id','unitprice',
        'sub_category_id', 'minimum_stock','barcode','product_type','current_stock','raw_material_type_id',
        'nb_boxes_1','nb_boxes_1_price','nb_boxes_2','nb_boxes_2_price','nb_boxes_3','nb_boxes_3_price','original_price',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6','sale_price','lot_qty',
        'field7',
        'field8',
        'field9',
        'field10','size','location','class_a_price','class_b_price','class_c_price','top_search','new','featured',
        'brand_id','item_box','upc_number','sub_sub_categoryid',
        'best_selling',
        'deal_of_the_day',
        'deal_date',
        'special_price','product_dropdown_1_id','product_dropdown_2_id'
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

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id','id');
    }

    
    public function product_dropdown_1()
    {
        return $this->belongsTo(ProductDropDown1::class, 'product_dropdown_1_id','id');
    }

    public function product_dropdown_2()
    {
        return $this->belongsTo(ProductDropDown2::class, 'product_dropdown_2_id','id');
    }
    
  
  

    public function raw_material_type()
    {
        return $this->belongsTo(RawMaterialType::class, 'raw_material_type_id','id');
    }
    

    public function items()
    {
        return $this->hasMany(Item::class, 'product_id', 'id');
    }

   public function lots()
    {
        return $this->hasMany(Lots::class, 'product_id', 'id');
    }


    public function salesOrders()
    {
        return $this->hasMany(SalesOrderItems::class, 'item_id', 'id');
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
        return $this->hasMany(Attribute::class, 'product_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'product_id', 'id');
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

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'product_id', 'id');
    }

    public function conversions()
    {
        return $this->hasMany(Conversion::class, 'product_id', 'id');
    }
    

    public function categoryd()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_categoryd()
    {
        return $this->belongsTo(SubCategory::class, 'sub_categoryid', 'id');
    }


    public function warehoused()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }


    public function getTextAttribute()
    {
        return $this->attributes['code'] .' - '. $this->attributes['description'].' - <span style="color:red;">[('. $this->attributes['current_stock'].' '. $this->attributes['unit'].')]</span>';
    }
}
