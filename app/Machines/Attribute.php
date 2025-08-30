<?php

namespace App\Machines;

use Illuminate\Database\Eloquent\Model;
use App\Machines\Machines;
use App\Machines\Attribute as AttributeValue;
use App\MachineAttributes\MachineAttributes as AttributeName;
use App\Support\Search;

class Attribute extends Model {

    use Search;
    protected $search = [
        'attribute_id'
    ];
    protected $table = 'machines_attributes';

    protected $fillable = [
        'attribute_id', 'attribute_value', 'machine_id'
    ];

    public function machine ()
    {
        return $this->belongsTo(Machines::class, 'machine_id', 'id');
    }

   
    // public function attribute()
    // {
    //     return $this->hasMany(AttributeName::class, 'product_id', 'id');
    // }


    protected $appends = [
        'text'
    ];

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
        return $this->attributes['id'] .' - '. $this->attributes['attribute_id'];;
    }


}
