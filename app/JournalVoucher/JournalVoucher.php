<?php

namespace App\JournalVoucher;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Invoice\Invoice;
use App\Bill\Bill;
use App\SalesOrder\SalesOrder;
use App\PurchaseOrder\PurchaseOrder;

class JournalVoucher extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const POSTED = 2;
    
    protected $connection = "mysql";
    protected $table = 'journal_vouchers';

    protected $search = [
        'reference', 'date', 'number'
    ];

    protected $columns = [
        'id',
        'number',
        'currency_id',
        'currency_name',
        'document_date',
        'document_type',
        'date',
        'exchange_rate',
        'vat_rate',
        'reference',
        'user_id',
        'created_by',
        'year_date',
        'total_debit',
        'total_credit',
        'document_id',
        'document_number',
        'document_date',
        'document_total',
        'document_currency_id',
        'terms',
        'status_id',
        'manual_type',
        
    ];

    protected $fillable = [
        'number',
        'currency_id',
        'currency_name',
        'document_type',
        'date',
        'exchange_rate',
        'vat_rate',
        'reference',
        'user_id',
        'created_by',
        'year_date',
        'total_debit',
        'total_credit',
        'document_id',
        'document_number',
        'document_date',
        'document_total',
        'document_currency_id',
        'terms',
        'status_id',
        'manual_type',
        
    ];

    protected $appends = ['text'];

    protected $casts = [
        'status_id'=> 'integer',
        'journal_id'=> 'integer',
        
    ];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
 
    public function invoices()
    {
        return $this->belongsTo(Invoice::class,'document_id','id');
    }

    public function bills()
    {
        return $this->belongsTo(Bill::class,'document_id','id');
    }

    public function purchase_orders()
    {
        return $this->belongsTo(PurchaseOrder::class,'document_id','id');
    }

    public function sales_orders()
    {
        return $this->belongsTo(SalesOrder::class,'document_id','id');
    }


    public function getTextAttribute()
    {
        return '<span style="color:red;"> ('.$this->attributes['number'] .'</span>) - '. $this->attributes['date'] .' - (<b>Debit: </b>'. $this->attributes['total_debit'] .')'
        .' - (<b>Cebit: </b>'. $this->attributes['total_credit'] .')';
    }
 
}
