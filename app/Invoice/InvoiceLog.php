<?php

namespace App\Invoice;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\ClientPayment\Item as ClientPaymentItem;
use App\AdvancePayment\Item as AdvancePaymentItem;
use App\Currency;
use App\Uom;
use App\Client;
use App\PaymentCondition;
use App\DeliveryCondition;

class InvoiceLog extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    // const CONFIRMED = 6;
    const PARTIALLY_PAID = 3;
    const PAID = 4;
    const VOID = 5;
    const ADJUSTED = 7;
    const REOPEN = 9;

    protected $connection = "mysql";
    protected $table = 'invoices_log';

    protected $search = [
        'reference', 'date', 'due_date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'client_id', 'number', 'reference', 'date', 'due_date','delivery_date','paymentcondition_id','deliverycondition_id', 'sub_total',
        'discount', 'total', 'terms', 'amount_paid', 'status_id','debit_amount','credit_amount','created_at','exchangerate','vat_status','vatrate',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10','discount_usd','discount_per','price_class','discount_percentage',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $fillable = [
        'user_id', 'client_id','reference', 'currency_id', 'date', 'due_date','delivery_date','paymentcondition_id','deliverycondition_id',
        'discount', 'terms','debit_amount','credit_amount','exchangerate','vat_status','vatrate',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10','discount_usd','discount_per','price_class','discount_percentage',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $appends = ['is_editable', 'is_overdue'];

    protected $casts = [
        'status_id'=> 'integer',
    ];
    
    public function items()
    {
        return $this->hasMany(ItemLog::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'uom_code', 'name');
    }

    
    public function deliverycondition()
    {
        return $this->belongsTo(DeliveryCondition::class, 'deliverycondition_id', 'id');
    }

    public function paymentcondition()
    {
        return $this->belongsTo(PaymentCondition::class, 'paymentcondition_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'user_id','id');
    }

    public function clientPayments()
    {
        return $this->hasMany(ClientPaymentItem::class, 'invoice_id', 'id');
    }

    public function advancePayments()
    {
        return $this->hasMany(AdvancePaymentItem::class, 'invoice_id', 'id');
    }
    public function debitNotes()
    {
        return $this->hasMany(DebitNotesItem::class, 'invoice_id', 'id');
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNotesItem::class, 'invoice_id', 'id');
    }
    /**
     * Get all of the owning invoiceable models.
     */
    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1]);
    }

    public function getIsOverdueAttribute()
    {
        return is_null($this->attributes['due_date'])
            ? false
            : date('Y-m-d') > $this->attributes['due_date'];
    }

    public function getParenttypeAttribute()
    {
        $type = isset($this->attributes['invoiceable_type']) ? $this->attributes['invoiceable_type'] : null;
        switch ($type) {
            case 'App\Quotation\Quotation':
                return 'Quotation';
                break;

            case 'App\SalesOrder\SalesOrder':
                return 'SalesOrder';
                break;

            default:
                return null;
                break;
        }
    }
}
