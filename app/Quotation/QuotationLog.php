<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\AdvancePayment\AdvancePayment;
use App\SalesOrder\SalesOrder;
use App\Invoice\Invoice;
use App\Currency;
use App\Client;
use App\Uom;
use App\PaymentCondition;
use App\DeliveryCondition;
use App\ExchangeRate\ExchangeRate;

class QuotationLog extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    const ACCEPTED = 3;
    const DECLINED = 4;
    const SALES_ORDERED = 5;
    const INVOICED = 6;

    protected $connection = "mysql";
    protected $table = 'quotations_log';

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'number', 'reference', 'date','due_date','delivery_date','paymentcondition_id','deliverycondition_id', 'sub_total',
        'discount', 'total', 'terms', 'created_at','exchangerate','vat_status','vatrate','price_class',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $fillable = [
        'client_id', 'reference', 'currency_id', 'date','due_date','delivery_date','paymentcondition_id','deliverycondition_id',
        'discount', 'terms','exchangerate','vat_status','vatrate','price_class',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $casts = [
        'status_id'=> 'integer',
    ];
    protected $appends = ['is_editable'];

    public function items()
    {
        return $this->hasMany(ItemLog::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientd()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'quotation_id', 'id');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'uom_id', 'id');
    }

    public function deliverycondition()
    {
        return $this->belongsTo(DeliveryCondition::class, 'deliverycondition_id', 'id');
    }
    

    public function paymentcondition()
    {
        return $this->belongsTo(PaymentCondition::class, 'paymentcondition_id', 'id');
    }

    public function advancePayments()
    {
        return $this->hasMany(AdvancePayment::class, 'quotation_id', 'id');
    }


    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }
}
