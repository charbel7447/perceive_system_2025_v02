<?php

namespace App\SellerPayment;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Client;
use App\Currency;
use App\SalesOrder\SalesOrder;

class SellerPayment extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const PARTIALLY_PAID = 2;
    const PAID = 3;

    protected $connection = "mysql";
    protected $table = 'seller_payments';

    protected $search = [
        'user_id',
        'number',
        'seller_id',
        'order_id',
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
    ];

    protected $columns = [
        'user_id',
        'number',
        'seller_id',
        'order_id',
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
    ];

    protected $fillable = [
        'user_id',
        'number',
        'seller_id',
        'order_id',
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
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sales_order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'id');
    }
    

    public function items()
    {
        return $this->hasMany(Item::class, 'client_payment_id', 'id');
    }
}
