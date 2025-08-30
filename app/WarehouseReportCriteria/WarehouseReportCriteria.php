<?php

namespace App\WarehouseReportCriteria;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Category;
use App\Currency;
use App\Warehouse;
use App\Uom;
use App\Vendor;
use App\Product\Product as ProductItem;
use App\Product\Item as Products;

class WarehouseReportCriteria extends Model
{
   protected $table = 'warehouses_report_criteria';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'user_id', 'warehouse_id', 'category_id', 'product_id','vendor_id','uom_id','from_date','to_date','created_by','created_at'];

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
       return $this->belongsTo(Products::class);
   }
}
