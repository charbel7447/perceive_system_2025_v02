<?php

namespace App\ExchangeRate;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\AdvancePayment\AdvancePayment;
use App\SalesOrder\SalesOrder;
use App\Invoice\Invoice;
use App\Currency;
use App\Client;
use App\DeliveryCondition;

class ExchangeRate extends Model
{
    use Search;
    use HasManyRelation;

    protected $table = 'exchange_rate';

    protected $search = [
        'currency1', 'currency2', 'value1', 'value2','exchangedate','created_by'
    ];

    protected $columns = [
        'currency1', 'currency2', 'value1', 'value2','exchangedate','created_by'
    ];

    protected $fillable = [
        'currency1', 'currency2', 'value1', 'value2','exchangedate','created_by'
    ];

    protected $casts = [
        'status_id'=> 'integer',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency1','id');
    }

    public function currency1()
    {
        return $this->belongsTo(Currency::class,'currency2','id');
    }

  
}
