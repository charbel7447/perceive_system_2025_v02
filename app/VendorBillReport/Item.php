<?php

namespace App\VendorBillReport;

use Illuminate\Database\Eloquent\Model;
use App\Uom;
use App\Vendor;
use App\Bill\Bill;
use App\Bill\Item as BillItem;
use App\PurchaseOrder\PurchaseOrder;

class Item extends Model
{
    protected $table = 'vendor_bills_report_items';

    protected $fillable = [
        'user_id',
        'created_by',
        'from_date',
        'to_date',
        'vendor_id',
        'amount_paid',
        'total',
        'exchangerate',
        'bill_date',
        'bill_id',
        'purchase_order_id',
    ];


    public function bills()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }

    public function bill_items()
    {
        return $this->belongsTo(BillItem::class, 'bill_id', 'bill_id');
    }
    
    public function purchase_orders()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
    
    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }


}
