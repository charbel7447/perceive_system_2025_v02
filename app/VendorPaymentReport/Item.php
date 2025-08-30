<?php

namespace App\VendorPaymentReport;

use Illuminate\Database\Eloquent\Model;
use App\Uom;
use App\VendorPayment\VendorPayment;
use App\Bill\Bill;
use App\Vendor;

class Item extends Model
{
    protected $table = 'vendor_payments_report_items';

    protected $fillable = [
        'user_id',
        'created_by',
        'from_date',
        'to_date',
        'vendor_id',
        'vendor_payment_id',
        'bill_id',
        'amount_applied',
        'amount_applied_lbp',
        'amount_applied_lbp_rate',
        'amount_applied_vat',
        'amount_applied_vat_rate',
        'payment_date',
    ];


    public function vendor_payments()
    {
        return $this->belongsTo(VendorPayment::class, 'vendor_payment_id', 'id');
    }

    public function vendor_bills()
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
    

    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }


}
