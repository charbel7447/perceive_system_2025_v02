<?php

namespace App\ShipperPayment;

use Illuminate\Database\Eloquent\Model;
use App\ShipperBill\ShipperBill;

class Item extends Model
{
    protected $table = 'shipper_payment_items';

    protected $fillable = [
        'shipper_bill_id', 'amount_applied','amount_applied_lbp','amount_applied_lbp_rate','comment'
    ];

    public function shipper_bill()
    {
        return $this->belongsTo(ShipperBill::class, 'shipper_bill_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ShipperPayment::class, 'shipper_payment_id', 'id');
    }
}
