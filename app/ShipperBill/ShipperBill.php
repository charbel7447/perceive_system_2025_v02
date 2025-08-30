<?php

namespace App\ShipperBill;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\PurchaseOrder\PurchaseOrder;
use App\ShipperPayment\Item as ShipperPaymentItem;
use App\Support\Search;
use App\Currency;
use App\Shipper;
use App\ContainerOrder\ContainerOrder;

class ShipperBill extends Model
{
    use Search;
    use HasManyRelation;

    const SENT = 1;
    const PARTIALLY_PAID = 2;
    const PAID = 3;
    const VOID = 4;
    const DRAFT = 5;
    const CONFIRMED = 6;
    const ADJUSTED = 7;
    
    protected $table = 'shipper_bills';

    protected $search = [
        'reference', 'date', 'due_date', 'terms', 'number', 'note'
    ];

    protected $columns = [
        'id', 'number', 'reference', 'date', 'due_date', 'total', 'subtotal',
        'terms', 'note', 'created_at', 'status_id','exchangerate','vat_status','uom_unit'
    ];

    protected $fillable = [
        'shipper_id', 'reference', 'currency_id', 'date', 'subtotal',
        'due_date', 'terms', 'note', 'status_id','exchangerate','vat_status','uom_unit'
    ];
    protected $casts = [
        'status_id'=> 'integer',
        'vat_status'=> 'integer',
    ];

    protected $appends = ['is_editable'];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class,'shipper_id','id');
    }

    public function containerOrder()
    {
        return $this->belongsTo(ContainerOrder::class, 'container_order_id', 'id');
    }

    public function shipperPayments()
    {
        return $this->hasMany(ShipperPaymentItem::class, 'shipper_bill_id', 'id');
    }

    // public function getIsEditableAttribute()
    // {
    //     return in_array($this->attributes['status_id'], [1]);
    // }
    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2, 3,6]);
    }
    
}
