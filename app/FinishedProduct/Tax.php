<?php

namespace App\FinishedProduct;

use Illuminate\Database\Eloquent\Model;
use App\FinishedProduct\FinishedProduct;

class Tax extends Model {

    protected $table = 'finished_product_taxes';

    protected $fillable = [
        'name', 'rate', 'tax_authority'
    ];

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id', 'id');
    }
}
