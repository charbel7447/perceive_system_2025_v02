<?php
// app/Models/StockCountProduct.php
namespace App\StockCount;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;

class StockCountProduct extends Model
{
    protected $fillable = [
        'stock_count_id', 'product_id', 'code', 'category_id', 'sub_category_id', 'sub_sub_category_id',
        'uom_id', 'current_stock', 'inventoried_stock', 'scanned_at'
    ];

    protected $appends = ['variance', 'variance_status']; // so it's available in arrays

    public function stockCount()
    {
        return $this->belongsTo(StockCount::class);
    }

    public function getVarianceAttribute(): int
    {
        // Positive = over, Negative = short
        return (int) $this->inventoried_stock - (int) $this->current_stock;
    }

    public function getVarianceStatusAttribute(): string
    {
        return $this->variance === 0
            ? 'match'
            : ($this->variance > 0 ? 'over' : 'short');
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

