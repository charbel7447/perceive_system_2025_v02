<?php

namespace App\ShipperStatement;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\ClientPayment\Item as ClientPaymentItem;
use App\AdvancePayment\Item as AdvancePaymentItem;
use App\Currency;
use App\Client;

class ShipperStatement extends Model
{
   protected $table = 'shipper_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}
