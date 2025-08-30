<?php

namespace App\VendorPayment;

use Illuminate\Database\Eloquent\Model;


class VendorPaymentLog extends Model
{
    protected $connection = "mysql";
    protected $table = 'vendor_payments_log';
}