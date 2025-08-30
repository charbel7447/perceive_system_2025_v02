<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Currency;
use App\Support\HasManyRelation;
use App\Support\Search;

class Item extends Model {

    protected $table = 'finished_product_items';

    use Search;
    use HasManyRelation;

    protected $search = [
        'price'
    ];
    protected $appends = [
        'text'
    ];

    protected $fillable = [
        'client_id', 'reference', 'price', 'currency_id'
        // ,'tax_name','tax_rate'
    ];

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function client1()
    {
        return $this->hasMany(Client::class,'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->attributes['price'];
    }
}
