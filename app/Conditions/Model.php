<?php

namespace App\Conditions;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
protected $table = 'payment_conditions';
	 public $primaryKey = 'id';
protected $fillable = ['name'];
  //protected $fillable = ['title','body','image'];
//protected $guarded = [];
}
