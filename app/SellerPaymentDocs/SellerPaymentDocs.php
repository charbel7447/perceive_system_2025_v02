<?php

namespace App\SellerPaymentDocs;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Client;
use App\Currency;
use App\SalesOrder\SalesOrder;
use App\Sellers\Sellers;


class SellerPaymentDocs extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const PARTIALLY_PAID = 2;
    const PAID = 3;

    protected $connection = "mysql";
    protected $table = 'seller_payments_docs';

    protected $search = [
        'user_id',
        'number',
        'seller_id',
        'total_amount_received',
        'sales_orders',
        'note',
        'created_by',
        'status_id',
    ];

    protected $columns = [
        'user_id',
        'number',
        'seller_id',
        'total_amount_received',
        'sales_orders',
        'note',
        'created_by',
        'status_id',
        'payment_option_id'
    ];

    protected $fillable = [
        'user_id',
        'number',
        'seller_id',
        'total_amount_received',
        'sales_orders',
        'note',
        'created_by',
        'status_id',
        'payment_option_id'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function seller()
    {
        return $this->belongsTo(Sellers::class, 'seller_id', 'id');
    }

    public function sales_order()
    {
        return $this->belongsTo(Invoice::class, 'order_id', 'id');
    }
    
  

    public function items()
    {
        return $this->hasMany(Item::class, 'client_payment_id', 'id');
    }
}
