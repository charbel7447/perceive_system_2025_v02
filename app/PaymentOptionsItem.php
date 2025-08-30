<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;

class PaymentOptionsItem extends Model
{
    protected $connection = "mysql";
    protected $table = 'payment_options_items';
}
