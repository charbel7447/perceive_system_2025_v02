<?php

namespace App\ReceiveOrderReport;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Currency;
use App\Vendor;
use App\Bill\Bill;
use App\Uom;
use App\PaymentCondition;
use App\DeliveryCondition;
use App\ExchangeRate\ExchangeRate;
use App\Priority;
use App\User;

class ReceiveOrderReport extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    const CONFIRMED = 3;
    const BILLED = 4;
    const CANCELLED = 5;
    const CLOSED = 6;
    const CONFIRMEDPO = 9;

    protected $table = 'receive_orders_report';

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id',
        'user_id',
        'purchase_order_id',
        'product_id',
        'vendor_id',
        'created_by',
        'from_date',
        'to_date',
    ];

    protected $fillable = [
        'user_id',
        'purchase_order_id',
        'product_id',
        'vendor_id',
        'created_by',
        'from_date',
        'to_date',
    ];
    protected $casts = [
        'status_id'=> 'integer',
    ];
    protected $appends = ['is_editable'];

    public function uom()
    {
        return $this->hasMany(Uom::class, 'uom_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Product::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class,'priority_id','id');
    }

    public function managern()
    {
        return $this->belongsTo(User::class,'manager_id','id');
    }


    public function purchase_order()
    {
        return $this->hasMany(PurchaseOrder::class, 'reference', 'number');
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }

}
