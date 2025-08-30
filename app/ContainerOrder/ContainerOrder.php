<?php

namespace App\ContainerOrder;

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
use App\Shipper;
use App\ContainerOrder\Product as ProductContainer;
use App\ContainerOrder\Item as Item;

class ContainerOrder extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    const CONFIRMED = 3;
    const BILLED = 4;
    const CANCELLED = 5;
    const CLOSED = 6;
    const RECEIVED = 7;
    const PARTIALLY_RECEIVED = 8;

    protected $table = 'container_orders';

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'number', 'reference', 'date', 'total', 'total_qty', 'total_weight', 'total_volume','vat_status',
        'terms', 'created_at','paymentcondition_id','deliverycondition_id','delivery_time','exchangerate',
        'shipper_fees','container_size','shipping','discount'
    ];

    protected $fillable = [
        'shipper_id', 'reference', 'total', 'total_qty', 'total_weight', 'total_volume', 'currency_id', 'date','vat_status', 'terms',
        'paymentcondition_id','deliverycondition_id','delivery_time','exchangerate',
        'shipper_fees','container_size','shipping','discount'
    ];
    protected $casts = [
        'status_id'=> 'integer',
    ];
    protected $appends = ['is_editable','text'];

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


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function container_products()
    {
        return $this->hasMany(ProductContainer::class,'container_order_item_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }
    
    

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function vendord()
    {
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }
    
    public function bills()
    {
        return $this->hasMany(Bill::class, 'purchase_order_id', 'id');
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['id'])) {
            return $this->attributes['number'];
        }

        // return $this->attributes['person'] .' - '. $this->attributes['company'];
        return 'ID:#'.$this->attributes['id'] .' - '.$this->attributes['number'];
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }

}
