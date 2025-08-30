<?php

namespace App\JobOrders;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Uom;
use App\Client;

class JobOrders extends Model
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

    protected $table = 'job_orders';

    protected $search = [
        'reference', 'date', 'due_date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'number', 'description','product_id','uom_id','client_id'
    ];

    protected $fillable = [
        'number', 'description','product_id','uom_id','client_id'
    ];

    protected $appends = ['is_editable', 'is_overdue'];

    protected $casts = [
        'status_id'=> 'integer',
    ];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
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

    public function client()
    {
        return $this->belongsTo(Client::class);
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
