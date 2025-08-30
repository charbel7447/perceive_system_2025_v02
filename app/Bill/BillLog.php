<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\PurchaseOrder\PurchaseOrder;
use App\VendorPayment\Item as VendorPaymentItem;
use App\Support\Search;
use App\Currency;
use App\Vendor;

class BillLog extends Model
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
    
    protected $table = 'bills_log';

    protected $search = [
        'reference', 'date', 'due_date', 'terms', 'number', 'note'
    ];

    protected $columns = [
        'id', 'number', 'reference', 'date', 'due_date', 'total', 'subtotal',
        'terms', 'note', 'created_at', 'status_id','exchangerate','vat_status','uom_unit',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $fillable = [
        'vendor_id', 'reference', 'currency_id', 'date', 'subtotal',
        'due_date', 'terms', 'note', 'status_id','exchangerate','vat_status','uom_unit',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];
    protected $casts = [
        'status_id'=> 'integer',
        'vat_status'=> 'integer',
    ];

    protected $appends = ['is_editable'];
    
    public function items()
    {
        return $this->hasMany(ItemLog::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function vendorPayments()
    {
        return $this->hasMany(VendorPaymentItem::class, 'bill_id', 'id');
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
