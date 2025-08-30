<?php

namespace App\Attributes;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
Use App\Attributes\Item;
Use App\Attributes\Attributes;

class Attributes extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'attributes';

    protected $search = [
       'description','number'
    ];

    protected $columns = [
        'id', 'description','created_at','number'
    ];

    protected $fillable = [
        'description' ,'created_at','number'
    ];

    protected $appends = [
        'text'
    ];

    // public function items()
    // {
    //     return $this->hasMany(Item::class, 'attribute_id','id');
    // }

    public function items()
    {
        return $this->hasMany(Item::class, 'attribute_id','id');
    }
    

    // public function test()
    // {
    //     return $this->hasMany(Item::class, 'attribute_id','id');
    // }

  
    // public function getTextAttribute()
    // {
    //     return $this->attributes['number'] .' - '. $this->attributes['description'];;
    // }

    public function getTextAttribute()
    {
        // return $this->attributes['description'];
        return $this->attributes['number'] .' - '. $this->attributes['description'];
    }
    
}
