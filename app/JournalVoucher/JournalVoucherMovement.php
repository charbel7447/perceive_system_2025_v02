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

class JournalVoucherMovement extends Model
{
    use Search;
    use HasManyRelation;


    
    protected $connection = "mysql";
    protected $table = 'journal_vouchers_movement';

    protected $search = [
        'reference', 'date', 'number','document_number','document_name'
    ];

  

    protected $fillable = [
        'journal_voucher_id',
        'number',
        'currency_id',
        'currency_name',
        'document_type',
        'document_id',
        'document_number',
        'document_date',
        'document_total',
        'document_currency_id',
        'date',
        'total_debit',
        'total_credit',
        'exchange_rate',
        'vat_rate',
        'reference',
        'user_id',
        'created_by',
        'year_date',
        'status_id',
        'saved_at',
        'posted_at',
        'posted_by',
        'terms',
        'document_name',
        'items',
        'type',
        'movement_date',
    ];
    
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


 
}
