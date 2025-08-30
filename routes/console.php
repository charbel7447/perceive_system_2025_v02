<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Schedule;
use Illuminate\Console\Scheduling\Schedule;
 
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('client:balance', function () {
    // $sch = new \App\ClientBalanceSchedule;
    //     $sch->date = Now();
    //     $sch->save;
        
    $all_clients = \App\Client::get();
    foreach($all_clients as $client){
        $invoices = \App\Invoice\Invoice::where('client_id','=',$client->id)->sum('total');
        $payments = \App\ClientPayment\ClientPayment::where('client_id','=',$client->id)->sum('amount_received');
        $credit = \App\CreditNote\CreditNote::where('client_id','=',$client->id)->sum('amount_received');
        $debit = \App\DebitNote\DebitNote::where('client_id','=',$client->id)->sum('amount_received');
        $advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client->id)->sum('amount_received');

        $balance = $invoices + $debit - $payments - $credit - $advance;

        Client::where('id', $client->id)->update(['balance' => $balance]);
    }
});
 

