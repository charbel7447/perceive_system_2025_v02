<?php

namespace App\PaymentVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use App\PaymentOptions;
use App\ChartOfAccount;
use App\Bill\Bill;

class Item extends Model
{
    protected $table = 'payment_voucher_items';

    protected $fillable = [
        'account_receivable_id',
        'account_receivable_number',
        'account_receivable_name',
        'payment_voucher_id',
        'payment_mode',
        'payment_option_id',
        'reference',
        'account_receivable_currency_code',
        'account_receivable_currency_id',
        'description',
        'date',
        'debit',
        'account_receivable_debit_vat_id',
        'account_receivable_debit_vat_code',
        'account_receivable_debit_vat_name',
        'debit_vat',
        'debit_usd'
    ];

   public function account_receivable_currency()
    {
        return $this->belongsTo(Currency::class,'account_receivable_currency_id','id');
    }

    public function payment_options()
    {
        return $this->belongsTo(PaymentOptions::class,'payment_option_id','id');
    }

    public function account_receivable()
    {
        return $this->belongsTo(ChartOfAccount::class,'account_receivable_id','id');
    }

        public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
      
}
