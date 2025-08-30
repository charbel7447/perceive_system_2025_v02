<?php

namespace App\StockCount;

use Illuminate\Database\Eloquent\Model;
use App\Product\Lots;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;


class StockCountLot extends Model
{
    protected $fillable = [
        'lot_id',
        'variance','balance',

                'stock_count_id', 'product_id', 'code', 'category_id', 'sub_category_id', 'sub_sub_category_id',
        'uom_id', 'current_stock', 'inventoried_stock', 'scanned_at'
    ];

    public function stockCount()
    {
        return $this->belongsTo(StockCount::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lots::class, 'lot_id');
    }

            public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function subSubCategory() {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id');
    }
}
