<?php

namespace App\ReceiveOrder;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\PurchaseOrder\Item as PurchaseOrderItem;
use App\Uom;

class Item extends Model
{
    protected $table = 'receive_order_items';

    protected $fillable = [
        'nb_of_lots','product_id', 'purchase_order_item_id','purchase_qty', 'qty','uom_id','uom_code','received_uom_id','received_uom_unit','received_uom_code'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function PurchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ReceiveOrder::class, 'receive_order_id', 'id');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'id','uom_id');
    }
}
