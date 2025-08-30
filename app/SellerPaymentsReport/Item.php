<?php

namespace App\SellerPaymentsReport;

use Illuminate\Database\Eloquent\Model;
use App\Uom;
use App\Client;
use App\Currency;
use App\SalesOrder\SalesOrder;
use App\SellerPaymentDocs\Item as PaymentItem;

class Item extends Model
{
    protected $table = 'seller_payments_report_items';

    public function clients()
    {
        return $this->belongsTo(Client::class,'client_id', 'id');
    }

    public function seller_payments()
    {
        return $this->belongsTo(PaymentItem::class, 'seller_payment_id', 'id')->orderBy('client_id','desc');
    }


}
