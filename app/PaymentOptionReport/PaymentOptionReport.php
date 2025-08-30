<?php

namespace App\PaymentOptionReport;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;

class PaymentOptionReport extends Model
{
    use Search;
    use HasManyRelation;
    protected $table = 'payment_options_report';
}
