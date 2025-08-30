<?php

namespace App\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'purchase_order_item_taxes';

    protected $fillable = [
        'name', 'rate', 'tax_authority'
    ];

    public function item1()
    {
        return $this->belongsTo(Item::class, 'purchase_order_item_id', 'id');
    }

    public function item()
    {
        return $this->hasMany(Item::class, 'purchase_order_item_id', 'id');
    }


}
