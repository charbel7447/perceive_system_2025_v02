<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\FinishedProduct\Machines;


class MachinesSettings extends Model {

    protected $table = 'finished_product_machines_settings';

    protected $fillable = [
      'settings_id', 'settings_name', 'settings_value'
    //   'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Machines::class, 'machine_id', 'id');
    }

   
    public function getTextAttribute()
    {
        return $this->attributes['id'] .' - '. $this->attributes['description'];;
    }


}
