<?php

namespace App\ClientPayment;

use Illuminate\Database\Eloquent\Model;
use App\Invoice\Invoice;

class Item extends Model
{
    protected $connection = "mysql";
    protected $table = 'client_payment_items';

    protected $fillable = [
        'invoice_id','total', 'amount_applied','amount_applied_lbp','amount_applied_lbp_rate','amount_applied_vat','note','amount_applied_vat_rate'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ClientPayment::class, 'client_payment_id', 'id');
    }
}
