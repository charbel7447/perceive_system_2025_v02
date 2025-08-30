<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Support\SearchProducts;



class ProductDropDown1 extends Model
{
    use Search;
    use SearchProducts;
    use HasManyRelation;

    // protected $connection = "sqlsrv2";
    protected $table = 'product_dropdown1';

   
    
    protected $search = [
        'id','name'
    ];


    protected $SearchProducts = [
        'name'
    ];

    protected $columns = [
        'id', 'name'
    ];

    protected $fillable = [
        'id','name'
    ];

    protected $appends = [
        'text'
    ];

  
    public function getTextAttribute()
    {
        return $this->attributes['id'] .' - '. $this->attributes['name'].' - <span style="color:red;">[('. $this->attributes['id'].')]</span>';
    }
}
