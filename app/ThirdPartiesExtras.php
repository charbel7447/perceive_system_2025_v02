<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Product\Product;
use App\Product\Item as ProductItem;

class ThirdPartiesExtras extends Model
{
    use Search;

    protected $connection = "mysql";
    protected $table = 'third_parties_extras';

    protected $search = [
        'name', 'number', 'description','currency_id'
    ];

    protected $columns = [
        'id', 'name', 'number', 'description', 'created_by','created_at','currency_id'
    ];

    protected $fillable = [
        'id', 'name',   'description', 'created_by','created_at','currency_id'
    ];

    protected $appends = [
        'text'
    ];


      public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['name'])) {
            return $this->attributes['number'];
        }

        return $this->attributes['number'] .' - '. $this->attributes['name'];;
    }

}
