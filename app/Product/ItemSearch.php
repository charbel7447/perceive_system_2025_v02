<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\Currency;
use App\Support\HasManyRelation;
use App\Support\Search;

class ItemSearch extends Model {

    protected $table = 'product_items';

    use Search;
    use HasManyRelation;

    protected $search = [
        'price'
    ];
    protected $appends = [
        'text'
    ];

    protected $fillable = [
        'vendor_id', 'reference', 'price', 'currency_id','tax_name','tax_rate'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function vendor1()
    {
        return $this->hasMany(Vendor::class,'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    // public function getTextAttribute()
    // {
    //     return $this->attributes['price'].' '.$this->attributes['code'].' - '. $this->attributes['company'];
    // }
    
    public function getTextAttribute()
    {
        return $this->attributes['vendor_id'].' - '. $this->attributes['company'];
    }
}
