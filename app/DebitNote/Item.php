<?php

namespace App\DebitNote;

use Illuminate\Database\Eloquent\Model;
use App\Invoice\Invoice;

class Item extends Model
{
    protected $table = 'debit_notes_items';

    protected $fillable = [
        'invoice_id', 'amount_applied'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(DeditNote::class, 'debit_notes_id', 'id');
    }
}
