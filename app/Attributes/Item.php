<?php

namespace App\Attributes;

use Illuminate\Database\Eloquent\Model;
Use App\Attributes\Attributes;

class Item extends Model {

    protected $table = 'attributes_value';

    protected $fillable = [
        'attribute_id','attribute_value',
    ];


    public function items()
    {
        return $this->hasMany(Attributes::class, 'attribute_id', 'id');
    }

    public function values()
    {
        return $this->belongsTo(Attributes::class, 'attribute_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['attribute_id'] .' - '. $this->attributes['attribute_value'];
    }

}
