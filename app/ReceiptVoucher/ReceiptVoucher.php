<?php

namespace App\ReceiptVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Client;

class ReceiptVoucher extends Model
{
    use Search;
    use HasManyRelation;

    const RECEIVED = 1;
    const PARTIALLY_APPLIED = 2;
    const APPLIED = 3;
    


    protected $table = 'receipt_vouchers';

    protected $search = [
        'number', 'date', 'note'
    ];

    protected $columns = [
        'id',
        'client_id',
        'client_name',
        'number',
        'date',
        'currency_id',
        'currency_code',
        'global_vat_percentage',
        'client_balance',
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
        'client_id',
        'client_name',
        'number',
        'date',
        'currency_id',
        'currency_code',
        'global_vat_percentage',
        'client_balance',
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
        return $this->hasMany(Item::class, 'receipt_voucher_id', 'id')->where('account_receivable_id','>',0);
    }

      public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

        public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

        public function invoices()
    {
        return $this->hasMany(Invoices::class, 'receipt_voucher_id', 'id');
    }
}
