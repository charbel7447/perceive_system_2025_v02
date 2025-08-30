<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Product\Product;
use App\Product\Item as ProductItem;


class SubCategory extends Model
{
    use Search;

    protected $connection = "mysql";
    protected $table = 'product_sub_categories';

    protected $search = [
        'name', 'number', 'description', 'created_by','category_id'
    ];

    protected $columns = [
        'id', 'name', 'number','category_id', 'description', 'created_by','created_at','order','status'
    ];

    protected $fillable = [
        'id', 'name', 'number', 'category_id', 'description', 'created_by','created_at','order','status'
    ];

    protected $appends = [
        'text'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Items::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function childs() {
        return $this->hasMany(SubCategory::class) ;
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['name'])) {
            return $this->attributes['number'];
        }

        return $this->attributes['number'] .' - '. $this->attributes['name'];;
    }

}
