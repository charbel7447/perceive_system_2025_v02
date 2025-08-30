<?php

namespace App\PurchaseOrder;

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

class PurchaseOrder extends Model
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
    const REOPEN = 9;

    protected $table = 'purchase_orders';

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'number', 'reference', 'date', 'total', 'subtotal','vat_status','discount','shipping',
        'terms', 'created_at','paymentcondition_id','deliverycondition_id','delivery_time','exchangerate',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $fillable = [
        'vendor_id', 'reference', 'subtotal', 'currency_id','shipping','discount', 'date','vat_status', 'terms','paymentcondition_id','deliverycondition_id','delivery_time','exchangerate',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];
    protected $casts = [
        'status_id'=> 'integer',
    ];
    protected $appends = ['is_editable','text'];

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
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

    public function currency()
    {
        return $this->belongsTo(Currency::class);
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
        return '<span style="color:red;"> ('.$this->attributes['number'] .'</span>) - '. $this->attributes['date'] .' - (<b>Amount: </b>'. $this->attributes['total'] .')';
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }

}
