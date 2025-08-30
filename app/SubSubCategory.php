<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Product\Product;
use App\Product\Item as ProductItem;


class SubSubCategory extends Model
{
    use Search;

    protected $connection = "mysql";
    protected $table = 'product_sub_sub_categories';

    protected $search = [
        'title', 'created_by','sub_category_id'
    ];

    protected $columns = [
        'id', 'title','sub_category_id',  'created_by','created_at','order','status'
    ];

    protected $fillable = [
        'id', 'title', 'sub_category_id', 'created_by','created_at','order','status'
    ];

    protected $appends = [
        'text'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_sub_category_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Items::class);
    }

    public function parent()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

    public function childs() {
        return $this->hasMany(SubSubCategory::class) ;
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['title'])) {
            return $this->attributes['id'];
        }

        return $this->attributes['id'] .' - '. $this->attributes['title'];;
    }

}
