<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\FinishedProduct\FinishedProduct;
use App\FinishedProduct\Attribute as AttributeValue;
use App\Attributes\Attributes as AttributeName;

class Attribute extends Model {

    protected $table = 'finished_product_attributes';

    protected $fillable = [
        'attribute_id', 'attribute_value'
    ];

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id', 'id');
    }

   
    // public function attribute()
    // {
    //     return $this->hasMany(AttributeName::class, 'product_id', 'id');
    // }


    
    public function attribute_name()
    {
        return $this->belongsTo(AttributeName::class, 'attribute_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(AttributeName::class, 'attribute_id', 'id');
    }

    public function values()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['id'] .' - '. $this->attributes['description'];;
    }


}
