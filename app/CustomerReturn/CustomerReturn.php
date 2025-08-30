<?php

namespace App\CustomerReturn;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Client;
use App\Invoice\Invoice;

class CustomerReturn extends Model
{
    use Search;
    use HasManyRelation;

    const RETURNED = 1;

    protected $table = 'customer_returns';

    protected $search = [
        'number', 'date', 'note'
    ];

    protected $columns = [
        'id', 'number', 'date', 'note', 'status_id', 'created_at'
    ];

    protected $fillable = [
        'client_id', 'date', 'note'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'receive_order_id', 'id');
    }

    public function invoiceOrder()
    {
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
}
