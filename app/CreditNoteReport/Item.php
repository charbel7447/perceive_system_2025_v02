<?php

namespace App\CreditNoteReport;

use Illuminate\Database\Eloquent\Model;
// use App\Product\Product;
// use App\Product\Item as ProductItem;

use App\Product\Product as ProductItem;
use App\Product\Item as Products;
use App\Uom;
use App\Client;
use App\Currency;
use App\CreditNote\CreditNote;
use App\Invoice\Invoice;

class Item extends Model
{
    protected $table = 'credit_note_report_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'client_id',
        'created_by',
        'from_date',
        'to_date',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function product()
    {
        return $this->belongsTo(ProductItem::class, 'product_id', 'id');
    }

    public function credit_notes()
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id', 'id');
    }

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
    
    
    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }


    public function clients()
    {
        return $this->belongsTo(Client::class,'client_id', 'id');
    }


}
