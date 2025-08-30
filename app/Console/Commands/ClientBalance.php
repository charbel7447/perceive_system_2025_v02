<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Client;
use App\Invoice\Invoice;

class ClientBalance extends Command
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $signature = 'client:balance';
    protected $description = 'client balance';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
   {
       parent::__construct();
   }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $all_clients = Client::get();
        foreach($all_clients as $client){
            $invoices = Invoice::where('client_id','=',$client->id)->sum('total');
            $payments = \App\ClientPayment\ClientPayment::where('client_id','=',$client->id)->sum('amount_received');
            $credit = \App\CreditNote\CreditNote::where('client_id','=',$client->id)->sum('amount_received');
            $debit = \App\DebitNote\DebitNote::where('client_id','=',$client->id)->sum('amount_received');
            $advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client->id)->sum('amount_received');

            $balance = $invoices + $debit - $payments - $credit - $advance;

            Client::where('id', $client->id)->update(['balance' => $balance]);

        }
    }
}
