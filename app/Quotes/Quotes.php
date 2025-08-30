<?php

namespace App\Quotes;

use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
 protected $table = 'quotations';
	 public $primaryKey = 'id';
protected $fillable = ['name','email','company','phone','location','product','quantity','message'];
  //protected $fillable = ['title','body','image'];
 
}
