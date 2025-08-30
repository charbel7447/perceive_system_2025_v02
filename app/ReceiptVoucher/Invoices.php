<?php

namespace App\ReceiptVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use App\PaymentOptions;
use App\ChartOfAccount;
use App\Invoice\Invoice;

class Invoices extends Model
{
    protected $table = 'receipt_voucher_invoices';

        public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
}
