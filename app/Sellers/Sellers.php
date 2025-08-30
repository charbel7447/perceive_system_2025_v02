<?php

namespace App\Sellers;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Invoice\Invoice;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;
use App\ClientPayment\ClientPayment;
use App\AdvancePayment\AdvancePayment;
use App\ExchangeRate\ExchangeRate;
use App\Currency;
use App\SellerPayment\SellerPayment;

class Sellers extends Model
{
    use Search;

    protected $connection = "mysql";
    protected $table = 'admins';

    protected $search = [
        'name','email'
    ];

    protected $columns = [
        'id','name', 'commission', 'email', 'commission_balance','username','phone','email_verified','currency_id'
    ];

    protected $fillable = [
        'name', 'commission', 'email', 'commission_balance','username','phone','email_verified','currency_id'
    ];

    protected $appends = [
        'text'
    ];

    protected $casts = [
        'vat_status'=> 'integer',
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function exchangerate()
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id', 'id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'seller_id', 'id');
    }

    public function sellerpayments()
    {
        return $this->hasMany(SellerPayment::class, 'seller_id', 'id');
    }

    public function seller_payments()
    {
        return $this->hasMany(SellerPayment::class, 'seller_id', 'id');
    }
    

    public function advancePayments()
    {
        return $this->hasMany(AdvancePayment::class, 'client_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class, 'client_id', 'id');
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['name'])) {
            return $this->attributes['email'];
        }

        return $this->attributes['name'] .' - '. $this->attributes['email'];;
    }
}
