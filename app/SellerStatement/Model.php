<?php

namespace App\SellerStatement;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
 protected $table = 'seller_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}