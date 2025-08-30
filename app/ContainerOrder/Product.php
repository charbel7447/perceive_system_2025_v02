<?php

namespace App\ContainerOrder;

use Illuminate\Database\Eloquent\Model;
use App\ContainerOrder\Item;

class Product extends Model
{
    protected $table = 'container_order_item_products';

    protected $fillable = [
        'name', 'rate', 'tax_authority'
    ];

    public function item1()
    {
        return $this->belongsTo(Item::class, 'container_order_item_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Item::class, 'container_order_item_id', 'id');
    }


}
