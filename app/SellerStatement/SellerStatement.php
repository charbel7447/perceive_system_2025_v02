<?php

namespace App\SellerStatement;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\ClientPayment\Item as ClientPaymentItem;
use App\AdvancePayment\Item as AdvancePaymentItem;
use App\Currency;
use App\Client;

class SellerStatement extends Model
{
   protected $table = 'seller_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}
