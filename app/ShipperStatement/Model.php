<?php

namespace App\ShipperStatement;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
 protected $table = 'shipper_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}