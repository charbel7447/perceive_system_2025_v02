<?php

namespace App\VendorStatement;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
 protected $table = 'vendor_statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}