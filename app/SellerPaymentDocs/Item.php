<?php

namespace App\SellerPaymentDocs;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Client;
use App\Currency;
use App\SalesOrder\SalesOrder;
use App\SellerPayment\SellerPayment;
use App\Invoice\Invoice;

class Item extends Model
{
    use HasManyRelation;


    protected $connection = "mysql";
    protected $table = 'seller_payments_docs_items';


    protected $columns = [
        'seller_payments_doc_id',
        'seller_payment_id',
        'user_id',
        'number',
        'seller_id',
        'client_id',
        'sales_sales_order_id',
        'currency_id',
        'total_amount',
        'order_amount',
        'amount_received',
        'amount_pending',
        'payment_date',
        'payment_at',
        'paid_by',
        'date',
        'year_date',
        'payment_mode',
        'payment_reference',
        'document',
        'note',
        'created_by',
        'status_id',
        'payment_date'
    ];

    protected $fillable = [
        'seller_payments_doc_id',
        'seller_payment_id',
        'user_id',
        'number',
        'seller_id',
        'client_id',
        'sales_order_id',
        'currency_id',
        'total_amount',
        'order_amount',
        'amount_received',
        'amount_pending',
        'payment_date',
        'payment_at',
        'paid_by',
        'date',
        'year_date',
        'payment_mode',
        'payment_reference',
        'document',
        'note',
        'created_by',
        'status_id',
        'payment_date'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller_payments()
    {
        return $this->belongsTo(SellerPayment::class, 'seller_payment_id', 'id');
    }

    public function sales_order()
    {
        return $this->belongsTo(Invoice::class, 'sales_order_id', 'id');
    }
    

}
