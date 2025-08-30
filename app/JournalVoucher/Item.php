<?php

namespace App\JournalVoucher;

use App\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model
{
    protected $connection = "mysql";
    protected $table = 'journal_voucher_items';

    protected $fillable = [
        'journal_voucher_id','account_id', 'account_name_en', 'account_name_ar','account_code','description','debit','credit'
    ];
    // ,'qty_on_hand'

      public function journalVoucher()
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id', 'id');
    }
    

    public function voucher()
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }
}
