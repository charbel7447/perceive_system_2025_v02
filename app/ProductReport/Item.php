<?php

namespace App\ProductReport;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;

class Item extends Model
{
    protected $table = 'products_report_items';
}
