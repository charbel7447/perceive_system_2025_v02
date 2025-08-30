<?php

namespace App\CustomerReturn;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Invoice\Item as PurchaseOrderItem;
use App\Uom;

class Item extends Model
{
    protected $table = 'customer_return_items';

    protected $fillable = [
        'item_id', 'invoice_item_id', 'invoiced_qty','uom_id','qty_returned','qty'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }

    public function InvoiceItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'invoice_item_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(CustomerReturn::class, 'customer_return_id', 'id');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'id','uom_id');
    }
}
