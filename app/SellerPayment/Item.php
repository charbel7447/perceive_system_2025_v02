<?php

namespace App\ClientPayment;

use Illuminate\Database\Eloquent\Model;
use App\SellerPayment\SellerPayment;

class Item extends Model
{
    protected $connection = "mysql";
    protected $table = 'seller_payments_items';

    protected $fillable = [
        'seller_payment_id', 'amount_applied','amount_applied_lbp','amount_applied_lbp_rate','amount_applied_vat','note','amount_applied_vat_rate'
    ];

    public function seller_payments()
    {
        return $this->belongsTo(SellerPayment::class, 'seller_payment_id', 'id');
    }

   
}
