<?php

namespace App\Statement;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
 protected $table = 'statement';
   public $primaryKey = 'id';
   protected $fillable = ['id', 'person', 'company', 'date', 'due_date' ,'created_at'];
}