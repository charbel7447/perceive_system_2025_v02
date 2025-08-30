<?php

namespace App\PaymentVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use App\PaymentOptions;
use App\ChartOfAccount;
use App\Bill\Bill;

class Bills extends Model
{
    protected $table = 'payment_voucher_bills';

        public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
}
