<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\Currency;
use App\Support\HasManyRelation;
use App\Support\Search;

class Inventory extends Model {

    protected $connection = "mysql";
    protected $table = 'product_inventories';
}
