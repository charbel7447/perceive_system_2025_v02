<?php

namespace App\ClientPayment;

use Illuminate\Database\Eloquent\Model;


class ClientPaymentLog extends Model
{
    protected $connection = "mysql";
    protected $table = 'client_payments_log';
}