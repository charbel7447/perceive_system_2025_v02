<?php

namespace App\ContainerReceiveOrder;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\ContainerOrder\Item as ContainerOrderItem;
use App\Uom;

class Item extends Model
{
    protected $table = 'container_receive_order_items';

    protected $fillable = [
        'product_id', 'container_order_item_id', 'quantity','uom_id','uom2_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function ContainerOrderItem()
    {
        return $this->belongsTo(ContainerOrderItem::class, 'container_order_item_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ContainerReceiveOrder::class, 'container_receive_order_id', 'id');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'id','uom_id');
    }
}
