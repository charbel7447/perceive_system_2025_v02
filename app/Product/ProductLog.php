<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;

class ProductLog extends Model
{
    protected $table = 'product_log';
}
