<?php

namespace App\Delivery;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
protected $table = 'delivery_conditions';
	 public $primaryKey = 'id';
protected $fillable = ['name'];
  //protected $fillable = ['title','body','image'];
//protected $guarded = [];
}
