<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $connection = "mysql";
    protected $table = 'bill_item_taxes';

    protected $fillable = [
        'name', 'rate', 'tax_authority'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'bill_item_id', 'id');
    }
}
