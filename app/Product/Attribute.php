<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Attribute\Item as AttributeValue;
use App\Attributes\Attributes as AttributeName;

class Attribute extends Model {

    protected $table = 'product_attributes';

    protected $fillable = [
        'attribute_id', 'attribute_value'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

   
    public function attribute()
    {
        return $this->hasMany(AttributeName::class, 'product_id', 'id');
    }

    public function attribute_name()
    {
        return $this->belongsTo(AttributeName::class, 'attribute_id', 'id');
    }

    public function attribute_value()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['id'] .' - '. $this->attributes['description'];;
    }


}
