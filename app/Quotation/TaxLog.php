<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Model;

class TaxLog extends Model
{
    protected $connection = "mysql";
    protected $table = 'quotation_log_items_taxes';

    protected $fillable = [
        'name', 'rate', 'tax_authority'
    ];

    public function item()
    {
        return $this->belongsTo(ItemLog::class, 'quotation_log_item_id', 'id');
    }
}
