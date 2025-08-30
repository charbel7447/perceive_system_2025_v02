<?php

namespace App\CreditNote;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Quotation\Quotation;
use App\Support\Search;
use App\Client;
use App\Currency;

class CreditNote extends Model
{
    use Search;
    use HasManyRelation;

    const RECEIVED = 1;
    const DEPOSITED = 2;
    const DRAWN = 3;
    const CANCELLED = 4;
    const PARTIALLYDRAWN = 5;
    
    
    protected $connection = "mysql";
    protected $table = 'credit_notes';

    protected $search = [
        'number', 'payment_date', 'payment_mode', 'payment_reference',
        'description'
    ];

    protected $columns = [
        'id','user_id','client_id','number', 'payment_date', 'payment_mode', 'payment_reference',
        'amount_received', 'description', 'created_at','amount_received_lbp','exchangerate',
        'payment_option_id','vat_received_usd','vat_received_lbp'
    ];

    protected $fillable = [
        'user_id','client_id', 'currency_id', 'payment_date', 'payment_mode',
        'payment_reference', 'description', 'amount_received','amount_received_lbp','exchangerate',
        'payment_option_id','vat_received_usd','vat_received_lbp'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'credit_notes_id', 'id');
    }
}
