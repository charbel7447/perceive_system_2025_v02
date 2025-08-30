<?php

namespace App\PaymentVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Vendor;

class PaymentVoucher extends Model
{
    use Search;
    use HasManyRelation;

    const RECEIVED = 1;
    const PARTIALLY_APPLIED = 2;
    const APPLIED = 3;
    


    protected $table = 'payment_vouchers';

    protected $search = [
        'number', 'date', 'note'
    ];

    protected $columns = [
        'id',
        'vendor_id',
        'vendor_name',
        'number',
        'date',
        'currency_id',
        'currency_code',
        'global_vat_percentage',
        'vendor_balance',
        'reference',
        'exchange_rate',
        'lines',
        'total',
        'total_debit',
        'total_debit_usd',
        'total_debit_vat',
        'balance_amount',
        'status_id',
        'vat_status',
    ];

    protected $fillable = [
        'vendor_id',
        'vendor_name',
        'number',
        'date',
        'currency_id',
        'currency_code',
        'global_vat_percentage',
        'vendor_balance',
        'reference',
        'exchange_rate',
        'lines',
        'total',
        'total_debit',
        'total_debit_usd',
        'total_debit_vat',
        'balance_amount',
        'status_id',
        'vat_status',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'payment_voucher_id', 'id')->where('account_receivable_id','>',0);
    }

      public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

        public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

        public function bills()
    {
        return $this->hasMany(Bills::class, 'payment_voucher_id', 'id');
    }
}
