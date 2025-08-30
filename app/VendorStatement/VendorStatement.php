<?php

namespace App\VendorStatement;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\ClientPayment\Item as ClientPaymentItem;
use App\AdvancePayment\Item as AdvancePaymentItem;
use App\Currency;
use App\Client;

class VendorStatement extends Model
{
   protected $table = 'vendor_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}
