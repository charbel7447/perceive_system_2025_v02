<?php

namespace App\SellerStatement;

use App\AdvancePayment\AdvancePayment;
use App\ClientPayment\ClientPayment;
use App\CreditNote\CreditNote;
use Illuminate\Database\Eloquent\Model;
use App\Invoice\Invoice;

class Item extends Model
{
    protected $table = 'seller_statement_items';

    protected $fillable = [
        'reference_number', 'amount_applied','statement_id','client_id','type'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function clientpayement()
    {
        return $this->belongsTo(ClientPayment::class, 'client_id', 'id');
    }

    public function advancepayment()
    {
        return $this->hasMany(AdvancePayment::class, 'client_id', 'id');
    }

    public function creditnote()
    {
        return $this->hasMany(CreditNote::class, 'client_id', 'id');
    }

    public function debitnote()
    {
        return $this->belongsTo(DeditNote::class, 'client_id', 'id');
    }
}
