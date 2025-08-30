<?php
// app/Models/StockCount.php
namespace App\StockCount;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $fillable = [
        'count_date', 'category_id', 'sub_category_id', 'sub_sub_category_id', 'created_by'
    ];

    public function products()
    {
        return $this->hasMany(StockCountProduct::class);
    }

        public function lots()
    {
        return $this->hasMany(StockCountLot::class)->where('current_stock','>',0);
    }
    
}
