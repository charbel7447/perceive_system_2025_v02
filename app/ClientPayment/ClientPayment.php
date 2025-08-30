<?php

namespace App\ClientPayment;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Support\Search;
use App\Client;
use App\Currency;

class ClientPayment extends Model
{
    use Search;
    use HasManyRelation;

    const RECEIVED = 1;
    const DEPOSITED = 2;

    protected $connection = "mysql";
    protected $table = 'client_payments';

    protected $search = [
        'number', 'payment_date', 'payment_mode', 'payment_reference',
        'amount_received',
        'amount_received_lbp',
        'amount_received_usd',
        'amount_applied_lbp',
        'amount_applied_lbp_rate',
        'amount_applied_vat',
        'amount_applied_vat_rate',
        'user_id'
    ];

    protected $columns = [
        'id', 'number', 'payment_date', 'payment_mode', 'payment_reference',
        'amount_received', 'created_at',
        'amount_received_lbp',
        'amount_received_usd',
        'amount_applied_lbp',
        'amount_applied_lbp_rate',
        'amount_applied_vat',
        'amount_applied_vat_rate',
        'user_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10',
        'payment_option_id'
    ];

    protected $fillable = [
        'client_id', 'currency_id', 'payment_date', 'payment_mode',
        'payment_reference',
        'amount_received_lbp',
        'amount_received_usd',
        'amount_applied_lbp',
        'amount_applied_lbp_rate',
        'amount_applied_vat',
        'amount_applied_vat_rate',
        'user_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10',
        'payment_option_id'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'client_payment_id', 'id');
    }
}
