<?php

namespace App\Http\Controllers\V2;

use Illuminate\Http\Request;
use App\Invoice\Invoice;
use App\PurchaseOrder\PurchaseOrder;
use App\SalesOrder\SalesOrder;
use App\Quotation\Quotation;
use App\Warehouse;
use App\Category;
use App\Bill\Bill;
use App\Vendor;
use App\Client;
use DB;
use Carbon\Carbon;
use App\ClientPayment\ClientPayment;
use App\VendorPayment\VendorPayment;
use App\Product\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return Product::all();

    }

}
