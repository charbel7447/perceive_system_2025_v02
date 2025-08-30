<?php

namespace App\PriceChangeReport;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product as ProductItem;
use App\Product\Item as Products;
use App\Uom;
use App\Vendor;
use App\Employee;
use App\Shipper;

use App\Currency;
use App\ContainerOrder\ContainerOrder;

class PriceChangeReport extends Model
{
    protected $table = 'price_changes_report';

    protected $fillable = [
        'user_id',
        'product_id',
        'vendor_id',
        'created_by',
        'from_date',
        'qty_received',
        'to_date',
    ];

}
